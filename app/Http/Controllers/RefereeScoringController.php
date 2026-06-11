<?php

namespace App\Http\Controllers;

use App\Models\ActiveCourtReferee;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumberMergeDetail;
use App\Models\RandoriMatchResult;
use App\Models\Referee;
use App\Models\RefereeScoreDetail;
use App\Models\Registration;
use App\Models\ScheduleReferee;
use App\Models\Technique\Technique;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class RefereeScoringController extends Controller
{
    /**
     * Renders the immersive referee scoring dashboard (Svelte).
     */
    public function index(): Response
    {
        return Inertia::render('RefereeScoring');
    }

    /**
     * Fetch the current active match and scoring state for the referee.
     */
    public function state(): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $isTabletMode = ! empty($user->judge_index) && ! empty($user->court_id);
        $referee = null;
        $activeMatch = null;
        $assignedCourt = null;
        $assignedSession = null;
        $assignedRundown = null;
        $judgeIndex = null;

        // ─── 1. Identify Identity & Role ───────────────────────────
        $activeDrawing = null;
        if ($isTabletMode) {
            $assignedCourt = Court::find($user->court_id);
            $judgeIndex = $user->judge_index;

            if ($assignedCourt && $assignedCourt->active_match_id) {
                $activeMatch = $assignedCourt->activeMatch;

                if ($assignedCourt->active_drawing_id) {
                    $activeDrawing = DrawingMatchNumber::with('pool')->find($assignedCourt->active_drawing_id);
                }
                if (! $activeDrawing) {
                    $activeDrawing = DrawingMatchNumber::with('pool')->where('match_number_id', $assignedCourt->active_match_id)
                        ->where('court_id', $assignedCourt->id)
                        ->first();
                }

                if ($activeDrawing) {
                    $assignedSession = $activeDrawing->sessionTime;
                    $assignedRundown = $activeDrawing->rundown;
                }

                // Priority 1: Check ActiveCourtReferee (Manual override)
                $activeAssignment = ActiveCourtReferee::with('referee')
                    ->where('court_id', $assignedCourt->id)
                    ->where('judge_index', $judgeIndex)
                    ->first();

                if ($activeAssignment) {
                    $referee = $activeAssignment->referee;
                } else {
                    // Priority 2: Fallback to Schedule (Auto detection)
                    if ($activeDrawing) {
                        $schedule = ScheduleReferee::with('referee')
                            ->where('court_id', $assignedCourt->id)
                            ->where('judge_index', $judgeIndex)
                            ->where('rundown_id', $activeDrawing->rundown_id)
                            ->where('session_time_id', $activeDrawing->session_time_id)
                            ->first();

                        if ($schedule) {
                            $referee = $schedule->referee;
                        }
                    }
                }
            }
        } else {
            // PERSONAL MODE: Referee is fixed, Court/Role are dynamic
            $referee = Referee::where('user_id', $user->id)->first();

            if ($referee) {
                // Priority 1: Check ActiveCourtReferee (Manual override)
                $activeAssignment = ActiveCourtReferee::with(['court.activeMatch'])
                    ->where('referee_id', $referee->id)
                    ->first();

                if ($activeAssignment && $activeAssignment->court?->active_match_id) {
                    $activeMatch = $activeAssignment->court->activeMatch;
                    $assignedCourt = $activeAssignment->court;
                    $judgeIndex = $activeAssignment->judge_index;

                    if ($assignedCourt->active_drawing_id) {
                        $activeDrawing = DrawingMatchNumber::with('pool')->find($assignedCourt->active_drawing_id);
                    }
                    if (! $activeDrawing) {
                        $activeDrawing = DrawingMatchNumber::with('pool')->where('match_number_id', $assignedCourt->active_match_id)
                            ->where('court_id', $assignedCourt->id)
                            ->first();
                    }

                    if ($activeDrawing) {
                        $assignedSession = $activeDrawing->sessionTime;
                        $assignedRundown = $activeDrawing->rundown;
                    }
                }

                // Priority 2: Fallback to Schedule (Auto detection)
                if (! $activeMatch) {
                    $mySchedules = ScheduleReferee::with(['court.activeMatch', 'court.activeDrawing', 'sessionTime', 'rundown'])
                        ->where('referee_id', $referee->id)
                        ->whereNotNull('court_id')
                        ->get();

                    foreach ($mySchedules as $schedule) {
                        if (! $schedule->court_id || ! $schedule->court) {
                            continue;
                        }

                        $court = $schedule->court;
                        if (! $court->active_match_id || ! $court->activeMatch) {
                            continue;
                        }

                        // Verify active drawing matches session/rundown
                        $activeDrawing = $court->activeDrawing;
                        if ($activeDrawing) {
                            $sessionMatch = $activeDrawing->session_time_id == $schedule->session_time_id
                                && $activeDrawing->rundown_id == $schedule->rundown_id;

                            if (! $sessionMatch) {
                                continue;
                            }
                        }

                        $activeMatch = $court->activeMatch;
                        $assignedCourt = $court;
                        $assignedSession = $schedule->sessionTime;
                        $assignedRundown = $schedule->rundown;
                        $judgeIndex = $schedule->judge_index;

                        if ($activeDrawing) {
                            $activeDrawing->load('pool');
                        } else {
                            $activeDrawing = DrawingMatchNumber::with('pool')->where('match_number_id', $assignedCourt->active_match_id)
                                ->where('court_id', $assignedCourt->id)
                                ->first();
                        }
                        break;
                    }
                }
            }
        }

        if ($activeMatch) {
            $activeMatch->load('ageGroup');
        }

        // Determine if participant has been called on court
        $participantCalled = false;
        if ($activeMatch) {
            $participantCalled = $activeMatch->draft_type === 'embu'
                ? ! is_null($activeMatch->active_registration_id)
                : ! is_null($activeMatch->active_bracket_node);
        }

        $isFormOpen = false;
        if ($participantCalled) {
            $isFormOpen = true;
        } else {
            $activeDrawingId = $assignedCourt?->active_drawing_id;
            if ($activeMatch && $activeDrawingId) {
                $isFormOpen = true;
            }
        }

        // ─── 2. Resolve Active Match Metadata ──────────────────────
        $activeContingentName = '-';
        $activeRoundLabel = $activeDrawing?->round ?? ($activeMatch?->round ?? ($assignedRundown?->name ?? '-'));
        $activeTechniqueLabel = '-';
        $activeTechniqueList = [];
        $activeAthleteNames = [];
        $activeIsTeamCategory = false;
        $matchNumberIds = [];
        $specificMatchId = null;

        if ($activeMatch) {
            $matchNumberIds = [$activeMatch->id];
            if ($activeMatch->mergeDetail) {
                $matchNumberIds = MatchNumberMergeDetail::where('match_number_merge_id', $activeMatch->mergeDetail->match_number_merge_id)
                    ->pluck('match_number_id')
                    ->toArray();
            }

            // Identify specific match from active drawing
            $activeDrawing = $activeDrawing ?? $assignedCourt?->activeDrawing;
            if ($activeDrawing && in_array($activeDrawing->match_number_id, $matchNumberIds)) {
                $specificMatchId = $activeDrawing->match_number_id;
            } else {
                $specificMatchId = $activeMatch->id;
            }

            if ($activeMatch->draft_type === 'embu') {
                if ($activeMatch->active_registration_id) {
                    $registration = Registration::with([
                        'contingent',
                        'athletes.matchNumbers' => fn ($query) => $query
                            ->whereIn('match_numbers.id', $matchNumberIds)
                            ->wherePivot('registration_id', $activeMatch->active_registration_id),
                    ])->find($activeMatch->active_registration_id);

                    if ($registration) {
                        $activeContingentName = $registration->contingent?->name ?? '-';
                        $activeAthletes = $registration->athletes
                            ->filter(fn ($athlete) => $athlete->matchNumbers->isNotEmpty())
                            ->values();

                        if ($activeDrawing && ! empty($activeDrawing->metadata['athlete_ids'])) {
                            $selectedAthleteIds = array_values((array) $activeDrawing->metadata['athlete_ids']);
                            if (! empty($selectedAthleteIds)) {
                                $activeAthletes = $activeAthletes->filter(fn ($athlete) => in_array($athlete->id, $selectedAthleteIds))->values();
                            }
                        }

                        $activeAthleteNames = $activeAthletes
                            ->pluck('name')
                            ->filter()
                            ->values()
                            ->all();
                        $activeIsTeamCategory = count($activeAthleteNames) > 2;

                        $selectedTechniqueIds = $activeAthletes
                            ->flatMap(fn ($athlete) => $athlete->matchNumbers->pluck('pivot.technique_ids'))
                            ->filter()
                            ->first();

                        if ($selectedTechniqueIds) {
                            $decodedTechniqueIds = json_decode($selectedTechniqueIds, true);
                            $techniqueIds = collect(is_array($decodedTechniqueIds) ? $decodedTechniqueIds : explode(',', (string) $selectedTechniqueIds))
                                ->map(fn ($id) => (int) $id)
                                ->filter()
                                ->values();

                            if ($techniqueIds->isNotEmpty()) {
                                $techniqueNames = Technique::whereIn('id', $techniqueIds)
                                    ->get()
                                    ->keyBy('id');

                                $selectedTechniqueNames = $techniqueIds
                                    ->map(fn ($id) => $techniqueNames->get($id)?->name)
                                    ->filter()
                                    ->values()
                                    ->all();

                                if ($selectedTechniqueNames !== []) {
                                    $activeTechniqueLabel = implode(', ', $selectedTechniqueNames);
                                    $activeTechniqueList = $selectedTechniqueNames;
                                }
                            }
                        }
                    }
                }
            } else {
                // Randori Match
                $bracketNode = $activeMatch->active_bracket_node;
                if ($bracketNode) {
                    $parts = explode('_', $bracketNode);
                    $bracket = $parts[0] ?? null;
                    $roundIdx = isset($parts[1]) ? (int) $parts[1] : 0;
                    $matchIdx = isset($parts[2]) ? (int) $parts[2] : 0;

                    $drawingData = $activeMatch->drawing_data ?? [];
                    if (is_string($drawingData)) {
                        $drawingData = json_decode($drawingData, true);
                    }

                    $targetBracket = $bracket === 'ub' ? 'upper_bracket' : ($bracket === 'lb' ? 'lower_bracket' : 'grand_final');
                    if ($targetBracket === 'grand_final') {
                        $matchInfo = $drawingData['grand_final'] ?? null;
                    } else {
                        $matchInfo = $drawingData[$targetBracket]['rounds'][$roundIdx][$matchIdx] ?? null;
                    }

                    if ($matchInfo) {
                        $akaName = $matchInfo['athlete1']['name'] ?? null;
                        $akaContingent = $matchInfo['athlete1']['contingent'] ?? null;
                        $shiroName = $matchInfo['athlete2']['name'] ?? null;
                        $shiroContingent = $matchInfo['athlete2']['contingent'] ?? null;

                        $names = [];
                        $contingents = [];
                        if ($akaName && $akaName !== 'BYE') {
                            $names[] = $akaName.' (Merah)';
                            if ($akaContingent) {
                                $contingents[] = $akaContingent;
                            }
                        }
                        if ($shiroName && $shiroName !== 'BYE') {
                            $names[] = $shiroName.' (Putih)';
                            if ($shiroContingent) {
                                $contingents[] = $shiroContingent;
                            }
                        }

                        $activeAthleteNames = $names;
                        if ($contingents !== []) {
                            $activeContingentName = implode(' vs ', array_unique($contingents));
                        }
                    }
                }
            }
        }

        // ─── 3. Load Existing Scores ───────────────────────────────
        $embuItems = [
            'goho_1' => 0, 'goho_2' => 0, 'goho_3' => 0,
            'juho_1' => 0, 'juho_2' => 0, 'juho_3' => 0,
            'ekspresi_1' => 0, 'ekspresi_2' => 0, 'ekspresi_3' => 0, 'ekspresi_4' => 0,
        ];
        $notes = '';
        $signature = null;
        $totalScore = 0;

        if ($activeMatch && $referee && $isFormOpen) {
            $id = null;
            if ($activeMatch->draft_type === 'embu') {
                $drawingId = $assignedCourt?->active_drawing_id;
                $id = $drawingId ?: $activeMatch->active_registration_id;
            } else {
                $bracketNode = $activeMatch->active_bracket_node;
                if ($bracketNode) {
                    $parts = explode('_', $bracketNode);
                    $bracketSection = $parts[0] ?? 'ub';
                    $bracketNodeIndex = (isset($parts[1]) && isset($parts[2])) ? $parts[1].'_'.$parts[2] : '0_0';

                    $randoriMatch = RandoriMatchResult::firstOrCreate(
                        ['match_number_id' => $activeMatch->id, 'bracket_node' => $bracketNode],
                        ['bracket_node_index' => $bracketNodeIndex, 'bracket_section' => $bracketSection]
                    );
                    $id = $randoriMatch->id;
                }
            }

            if ($id) {
                $existing = RefereeScoreDetail::where('match_number_id', $specificMatchId ?? $activeMatch->id)
                    ->where('referee_id', $referee->id)
                    ->where('scorable_id', $id)
                    ->first();

                if ($existing) {
                    if ($activeMatch->draft_type === 'embu') {
                        $embuItems = array_merge($embuItems, $existing->details);
                    }
                    $notes = $existing->notes;
                    $signature = $existing->signature;
                    $totalScore = (float) $existing->total_calculated_score;
                }
            }
        }

        // Get readable judge label
        $judgeLabel = $judgeIndex ? $this->getJudgeLabel($judgeIndex) : null;

        return response()->json([
            'referee' => $referee,
            'activeMatch' => $activeMatch,
            'activeDrawing' => $activeDrawing,
            'assignedCourt' => $assignedCourt,
            'assignedSession' => $assignedSession,
            'assignedRundown' => $assignedRundown,
            'judgeIndex' => $judgeIndex,
            'judgeLabel' => $judgeLabel,
            'activeContingentName' => $activeContingentName,
            'activeRoundLabel' => $activeRoundLabel,
            'activeTechniqueLabel' => $activeTechniqueLabel,
            'activeTechniqueList' => $activeTechniqueList,
            'activeAthleteNames' => $activeAthleteNames,
            'activeIsTeamCategory' => $activeIsTeamCategory,
            'isFormOpen' => $isFormOpen,
            'embuItems' => $embuItems,
            'notes' => $notes,
            'totalScore' => $totalScore,
            'signature' => $signature,
            'isTabletMode' => $isTabletMode,
            'currentActiveIdentifier' => $activeMatch ? $activeMatch->id.'_'.($assignedCourt?->active_drawing_id ?? $activeMatch->active_registration_id ?? $activeMatch->active_bracket_node) : null,
        ]);
    }

    /**
     * Handle real-time auto-saving of scoring details.
     */
    public function save(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Resolve referee & assignment
        $stateResult = $this->resolveRefereeState($user);
        if (! $stateResult || ! $stateResult['referee'] || ! $stateResult['activeMatch'] || ! $stateResult['judgeIndex']) {
            return response()->json(['success' => false, 'message' => 'Invalid assignment or active match.'], 400);
        }

        $referee = $stateResult['referee'];
        $activeMatch = $stateResult['activeMatch'];
        $assignedCourt = $stateResult['assignedCourt'];
        $judgeIndex = $stateResult['judgeIndex'];

        $drawingId = $assignedCourt?->active_drawing_id;
        if ($activeMatch->draft_type === 'embu') {
            $id = $drawingId ?: $activeMatch->active_registration_id;
            $scorableType = $drawingId ? DrawingMatchNumber::class : Registration::class;
        } else {
            return response()->json(['success' => false, 'message' => 'Kategori Randori tidak dinilai di panel ini.'], 400);
        }

        if (! $id) {
            return response()->json(['success' => false, 'message' => 'No active contestant.'], 400);
        }

        $embuItems = $request->input('embuItems', []);
        $notes = $request->input('notes', '');

        // Standardize comma and decimal representation, clamp 0.0 to 10.0
        foreach ($embuItems as $key => $val) {
            if (is_string($val)) {
                $val = str_replace(',', '.', $val);
            }
            $numericVal = is_numeric($val) ? (float) $val : 0;
            $embuItems[$key] = max(0.0, min(10.0, $numericVal));
        }

        // Calculate total score
        $totalScore = 0;
        foreach ($embuItems as $key => $val) {
            $totalScore += $val;
        }

        DB::transaction(function () use ($activeMatch, $referee, $id, $scorableType, $embuItems, $totalScore, $notes, $judgeIndex, $drawingId, $assignedCourt) {
            $targetMatchId = $this->getSpecificMatchId($activeMatch, $assignedCourt);

            // 1. Save Granular Details
            RefereeScoreDetail::updateOrCreate(
                [
                    'match_number_id' => $targetMatchId,
                    'referee_id' => $referee->id,
                    'scorable_type' => $scorableType,
                    'scorable_id' => $id,
                ],
                [
                    'judge_index' => $judgeIndex,
                    'details' => $embuItems,
                    'total_calculated_score' => $totalScore,
                    'notes' => $notes ?? '',
                ]
            );

            // 2. Sync to Main Table for Quick Access
            $column = 'judge_'.$judgeIndex;

            $registrationId = $activeMatch->active_registration_id;
            if (! $registrationId && $drawingId) {
                $drawing = DrawingMatchNumber::find($drawingId);
                $registrationId = $drawing?->registration_id;
            }

            EmbuScore::updateOrCreate(
                [
                    'match_number_id' => $targetMatchId,
                    'registration_id' => $registrationId,
                    'drawing_id' => $drawingId ?? null,
                ],
                [$column => $totalScore]
            );
        });

        return response()->json([
            'success' => true,
            'totalScore' => $totalScore,
        ]);
    }

    /**
     * Submit scores and signature.
     */
    public function submit(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $stateResult = $this->resolveRefereeState($user);
        if (! $stateResult || ! $stateResult['referee'] || ! $stateResult['activeMatch'] || ! $stateResult['judgeIndex']) {
            return response()->json(['success' => false, 'message' => 'Invalid assignment or active match.'], 400);
        }

        $referee = $stateResult['referee'];
        $activeMatch = $stateResult['activeMatch'];
        $assignedCourt = $stateResult['assignedCourt'];
        $judgeIndex = $stateResult['judgeIndex'];

        $signature = $request->input('signature');
        if (! $signature) {
            return response()->json(['success' => false, 'message' => 'Tanda tangan wajib diisi.'], 400);
        }

        $drawingId = $assignedCourt?->active_drawing_id;
        if ($activeMatch->draft_type === 'embu') {
            $id = $drawingId ?: $activeMatch->active_registration_id;
            $scorableType = $drawingId ? DrawingMatchNumber::class : Registration::class;
        } else {
            return response()->json(['success' => false, 'message' => 'Kategori Randori tidak dinilai di panel ini.'], 400);
        }

        if (! $id) {
            return response()->json(['success' => false, 'message' => 'Belum ada peserta yang dipanggil.'], 400);
        }

        $embuItems = $request->input('embuItems', []);
        $notes = $request->input('notes', '');

        // Standardize comma and decimal representation
        foreach ($embuItems as $key => $val) {
            if (is_string($val)) {
                $val = str_replace(',', '.', $val);
            }
            $numericVal = is_numeric($val) ? (float) $val : 0;
            $embuItems[$key] = max(0.0, min(10.0, $numericVal));
        }

        $totalScore = 0;
        foreach ($embuItems as $key => $val) {
            $totalScore += $val;
        }

        DB::transaction(function () use ($activeMatch, $referee, $id, $scorableType, $embuItems, $totalScore, $notes, $signature, $judgeIndex, $drawingId, $assignedCourt) {
            $targetMatchId = $this->getSpecificMatchId($activeMatch, $assignedCourt);

            // 1. Save Granular Details with Signature
            RefereeScoreDetail::updateOrCreate(
                [
                    'match_number_id' => $targetMatchId,
                    'referee_id' => $referee->id,
                    'scorable_type' => $scorableType,
                    'scorable_id' => $id,
                ],
                [
                    'judge_index' => $judgeIndex,
                    'details' => $embuItems,
                    'total_calculated_score' => $totalScore,
                    'notes' => $notes ?? '',
                    'signature' => $signature,
                ]
            );

            // 2. Sync to Main Table
            $column = 'judge_'.$judgeIndex;

            $registrationId = $activeMatch->active_registration_id;
            if (! $registrationId && $drawingId) {
                $drawing = DrawingMatchNumber::find($drawingId);
                $registrationId = $drawing?->registration_id;
            }

            EmbuScore::updateOrCreate(
                [
                    'match_number_id' => $targetMatchId,
                    'registration_id' => $registrationId,
                    'drawing_id' => $drawingId ?? null,
                ],
                [$column => $totalScore]
            );
        });

        return response()->json([
            'success' => true,
            'message' => 'Nilai Juri '.$judgeIndex.' telah disimpan.',
        ]);
    }

    /**
     * Resolves referee assignment and active match details.
     */
    private function resolveRefereeState($user): ?array
    {
        $isTabletMode = ! empty($user->judge_index) && ! empty($user->court_id);
        $referee = null;
        $activeMatch = null;
        $assignedCourt = null;
        $judgeIndex = null;

        if ($isTabletMode) {
            $assignedCourt = Court::find($user->court_id);
            $judgeIndex = $user->judge_index;

            if ($assignedCourt && $assignedCourt->active_match_id) {
                $activeMatch = $assignedCourt->activeMatch;

                $activeAssignment = ActiveCourtReferee::where('court_id', $assignedCourt->id)
                    ->where('judge_index', $judgeIndex)
                    ->first();

                if ($activeAssignment) {
                    $referee = $activeAssignment->referee;
                } else {
                    $activeDrawing = $assignedCourt->active_drawing_id ? DrawingMatchNumber::find($assignedCourt->active_drawing_id) : null;
                    if (! $activeDrawing) {
                        $activeDrawing = DrawingMatchNumber::where('match_number_id', $assignedCourt->active_match_id)
                            ->where('court_id', $assignedCourt->id)
                            ->first();
                    }

                    if ($activeDrawing) {
                        $schedule = ScheduleReferee::where('court_id', $assignedCourt->id)
                            ->where('judge_index', $judgeIndex)
                            ->where('rundown_id', $activeDrawing->rundown_id)
                            ->where('session_time_id', $activeDrawing->session_time_id)
                            ->first();

                        if ($schedule) {
                            $referee = $schedule->referee;
                        }
                    }
                }
            }
        } else {
            $referee = Referee::where('user_id', $user->id)->first();
            if ($referee) {
                $activeAssignment = ActiveCourtReferee::with(['court.activeMatch'])
                    ->where('referee_id', $referee->id)
                    ->first();

                if ($activeAssignment && $activeAssignment->court?->active_match_id) {
                    $activeMatch = $activeAssignment->court->activeMatch;
                    $assignedCourt = $activeAssignment->court;
                    $judgeIndex = $activeAssignment->judge_index;
                }

                if (! $activeMatch) {
                    $mySchedules = ScheduleReferee::with(['court.activeMatch', 'court.activeDrawing', 'sessionTime', 'rundown'])
                        ->where('referee_id', $referee->id)
                        ->whereNotNull('court_id')
                        ->get();

                    foreach ($mySchedules as $schedule) {
                        if (! $schedule->court_id || ! $schedule->court) {
                            continue;
                        }

                        $court = $schedule->court;
                        if (! $court->active_match_id || ! $court->activeMatch) {
                            continue;
                        }

                        $activeDrawing = $court->activeDrawing;
                        if ($activeDrawing) {
                            $sessionMatch = $activeDrawing->session_time_id == $schedule->session_time_id
                                && $activeDrawing->rundown_id == $schedule->rundown_id;

                            if (! $sessionMatch) {
                                continue;
                            }
                        }

                        $activeMatch = $court->activeMatch;
                        $assignedCourt = $court;
                        $judgeIndex = $schedule->judge_index;
                        break;
                    }
                }
            }
        }

        return [
            'referee' => $referee,
            'activeMatch' => $activeMatch,
            'assignedCourt' => $assignedCourt,
            'judgeIndex' => $judgeIndex,
        ];
    }

    private function getSpecificMatchId($activeMatch, $assignedCourt)
    {
        $matchNumberIds = [$activeMatch->id];
        if ($activeMatch->mergeDetail) {
            $matchNumberIds = MatchNumberMergeDetail::where('match_number_merge_id', $activeMatch->mergeDetail->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        }

        $activeDrawing = $assignedCourt?->activeDrawing;
        if ($activeDrawing && in_array($activeDrawing->match_number_id, $matchNumberIds)) {
            return $activeDrawing->match_number_id;
        }

        return $activeMatch->id;
    }

    private function getJudgeLabel(int $index): string
    {
        return match ($index) {
            1 => 'Wasit Nasional (Ketua)',
            2 => 'Wasit Daerah 1',
            3 => 'Wasit Daerah 2',
            4 => 'Wasit Pembantu 1',
            5 => 'Wasit Pembantu 2',
            default => 'Juri '.$index
        };
    }
}
