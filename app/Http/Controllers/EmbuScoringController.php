<?php

namespace App\Http\Controllers;

use App\Events\CourtUpdated;
use App\Events\MatchUpdated;
use App\Http\Requests\EmbuAdvanceToFinalRequest;
use App\Http\Requests\EmbuCallParticipantRequest;
use App\Http\Requests\EmbuFinishMatchRequest;
use App\Http\Requests\EmbuRequestTiebreakRequest;
use App\Http\Requests\EmbuSaveScoreRequest;
use App\Models\Athlete;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\MatchNumberMerge;
use App\Models\SchedulePanitera;
use App\Models\ScheduleReferee;
use App\Services\BracketService;
use App\Services\StateCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class EmbuScoringController extends Controller
{
    public function __construct(
        protected BracketService $bracketService,
        protected StateCache $stateCache,
    ) {}

    public function scoringEmbuState(Request $request, MatchNumber $matchNumber): JsonResponse
    {
        $urlRound = $request->query('round');
        $urlPoolId = $request->query('pool_id');

        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $matchNumber->id)
            ->first();

        $merge = null;
        if ($mergeDetails) {
            $merge = MatchNumberMerge::find($mergeDetails->match_number_merge_id);
            $matchNumberIds = DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        } else {
            $matchNumberIds = [$matchNumber->id];
        }

        $matchNumber->load([
            'athletes',
            'embuScores',
            'drawings.court',
            'drawings.sessionTime',
            'drawings.rundown',
            'drawings.pool',
            'ageGroup',
        ]);

        if ($urlRound) {
            $currentRound = $urlRound;
        } else {
            $hasFinalists = $matchNumber->embuScores
                ->where('round_label', 'Final')
                ->count() > 0;
            $currentRound = $hasFinalists ? 'Final' : 'Penyisihan';
        }

        $selectedPoolId = null;
        if ($urlPoolId) {
            $selectedPoolId = (int) $urlPoolId;
        } else {
            $firstDrawing = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)
                ->where('round', $currentRound)
                ->whereNotNull('pool_id')
                ->first();
            if ($firstDrawing) {
                $selectedPoolId = $firstDrawing->pool_id;
            }
        }

        // Fetch all drawings for the current merge group and round
        $drawingsQuery = DrawingMatchNumber::with(['matchNumber', 'registration.contingent', 'registration.athletes'])
            ->whereIn('match_number_id', $matchNumberIds)
            ->where('round', $currentRound);

        if ($currentRound === 'Penyisihan' && $selectedPoolId) {
            $drawingsQuery->where('pool_id', $selectedPoolId);
        }

        $drawingsList = $drawingsQuery->orderBy('sequence_number')->get();
        $pivotAthletes = Athlete::whereHas('matchNumbers', function ($query) use ($matchNumberIds) {
            $query->whereIn('match_numbers.id', $matchNumberIds);
        })
            ->with(['matchNumbers' => fn ($query) => $query->whereIn('match_numbers.id', $matchNumberIds)])
            ->get()
            ->flatMap(function ($athlete) {
                return $athlete->matchNumbers->map(function ($matchNumber) use ($athlete) {
                    return [
                        'key' => $matchNumber->id.':'.$matchNumber->pivot->registration_id,
                        'athlete' => $athlete,
                    ];
                });
            })
            ->groupBy('key')
            ->map(fn ($items) => $items->pluck('athlete')->unique('id')->values());

        $allScores = EmbuScore::whereIn('match_number_id', $matchNumberIds)
            ->where('round_label', $currentRound)
            ->get();

        $penyisihanScores = $currentRound === 'Final'
            ? EmbuScore::whereIn('match_number_id', $matchNumberIds)
                ->where('round_label', 'Penyisihan')
                ->orderByDesc('tiebreak_round')
                ->get()
                ->groupBy('registration_id')
            : collect();

        $registrations = $drawingsList->map(function ($drawing) use ($allScores, $currentRound, $pivotAthletes, $penyisihanScores) {
            $regId = $drawing->registration_id;
            $matchId = $drawing->match_number_id;
            $registration = $drawing->registration;

            $metaAthleteIds = $drawing->metadata['athlete_ids'] ?? [];

            if (! empty($metaAthleteIds)) {
                $athletes = $drawing->registration?->athletes->whereIn('id', $metaAthleteIds)->values() ?? collect();
            } else {
                $athletes = $pivotAthletes->get($matchId.':'.$regId, collect());
            }

            $score = $allScores->where('registration_id', $regId)
                ->where('match_number_id', $matchId)
                ->where('drawing_id', $drawing->id)
                ->sortByDesc('tiebreak_round')
                ->first();

            if (! $score) {
                $score = $allScores->where('registration_id', $regId)
                    ->where('match_number_id', $matchId)
                    ->whereNull('drawing_id')
                    ->sortByDesc('tiebreak_round')
                    ->first();
            }

            $scoreHistory = $allScores->where('registration_id', $regId)
                ->where('match_number_id', $matchId)
                ->where('drawing_id', $drawing->id)
                ->sortBy('tiebreak_round')
                ->values();

            if ($scoreHistory->isEmpty()) {
                $scoreHistory = $allScores->where('registration_id', $regId)
                    ->where('match_number_id', $matchId)
                    ->whereNull('drawing_id')
                    ->sortBy('tiebreak_round')
                    ->values();
            }

            $accumulatedScore = 0;
            $penyisihanScore = null;

            if ($currentRound === 'Final') {
                $penyisihanScore = $penyisihanScores->get($regId, collect())->first();

                if ($penyisihanScore) {
                    $accumulatedScore += $penyisihanScore->nilai_akhir;
                }
            }

            if ($score) {
                $accumulatedScore += $score->nilai_akhir;
            }

            return [
                'id' => $regId,
                'drawing_id' => $drawing->id,
                'match_number_id' => $matchId,
                'match_name' => $drawing->matchNumber?->name ?? '—',
                'is_group' => $drawing->matchNumber ? ($drawing->matchNumber->max_athletes > 1) : false,
                'athletes' => $athletes->unique('id')->values(),
                'contingent' => $registration?->contingent,
                'score' => $score,
                'score_history' => $scoreHistory,
                'penyisihan_score' => $penyisihanScore,
                'accumulated_score' => $accumulatedScore,
                'sequence_number' => $drawing->sequence_number ?? 999,
            ];
        });

        $registrations = $registrations->sort(function ($a, $b) {
            $rankA = $a['score']?->rank ?? 999;
            $rankB = $b['score']?->rank ?? 999;

            if ($rankA != $rankB) {
                return $rankA <=> $rankB;
            }

            return $a['sequence_number'] <=> $b['sequence_number'];
        })->values();

        $firstDrawingQuery = DrawingMatchNumber::with(['court', 'pool', 'sessionTime'])
            ->whereIn('match_number_id', $matchNumberIds)
            ->where('round', $currentRound);
        if ($currentRound === 'Penyisihan' && $selectedPoolId) {
            $firstDrawingQuery = $firstDrawingQuery->where('pool_id', $selectedPoolId);
        }
        $firstDrawing = $firstDrawingQuery->first();

        $availablePools = collect();
        if ($currentRound === 'Penyisihan') {
            $availablePools = DrawingMatchNumber::with('pool')
                ->whereIn('match_number_id', $matchNumberIds)
                ->where('round', 'Penyisihan')
                ->whereNotNull('pool_id')
                ->get()
                ->pluck('pool')
                ->unique('id')
                ->values();
        }

        $tiedIds = $this->bracketService->detectTies($matchNumberIds, $currentRound);

        if ($merge) {
            $mergedNames = MatchNumber::whereIn('id', $matchNumberIds)->pluck('name')->join(', ');
            $displayName = ($merge->name ?: 'Merged Group').' ('.$mergedNames.')';
        } else {
            $displayName = $matchNumber->name;
        }

        $courtId = $firstDrawing?->court_id;
        $court = $courtId ? Court::find($courtId) : null;
        $activeDrawingId = $court?->active_drawing_id;

        $assignedArbitrase = null;
        $assignedReferees = collect();
        $assignedKoordinators = collect();
        $assignedPaniteras = collect();

        if ($firstDrawing) {
            $assignedArbitrase = ScheduleReferee::with('referee.user')
                ->where('rundown_id', $firstDrawing->rundown_id)
                ->where('session_time_id', $firstDrawing->session_time_id)
                ->whereNull('court_id')
                ->where('judge_index', 0)
                ->first();

            $assignedReferees = ScheduleReferee::with('referee.user')
                ->where('rundown_id', $firstDrawing->rundown_id)
                ->where('session_time_id', $firstDrawing->session_time_id)
                ->where('court_id', $firstDrawing->court_id)
                ->where('judge_index', '>', 0)
                ->orderBy('judge_index')
                ->get();

            $assignedKoordinators = SchedulePanitera::with('user')
                ->where('rundown_id', $firstDrawing->rundown_id)
                ->where('session_time_id', $firstDrawing->session_time_id)
                ->where('court_id', $firstDrawing->court_id)
                ->where('role_type', 'koordinator')
                ->orderBy('slot_index')
                ->get();

            $assignedPaniteras = SchedulePanitera::with('user')
                ->where('rundown_id', $firstDrawing->rundown_id)
                ->where('session_time_id', $firstDrawing->session_time_id)
                ->where('court_id', $firstDrawing->court_id)
                ->where('role_type', 'panitera')
                ->orderBy('slot_index')
                ->get();
        }

        $timerState = $courtId ? Cache::get("court_{$courtId}_timer", [
            'status' => 'stopped',
            'elapsed_ms' => 0,
            'started_at_ms' => null,
            'countdown_end_ms' => null,
        ]) : [
            'status' => 'stopped',
            'elapsed_ms' => 0,
            'started_at_ms' => null,
            'countdown_end_ms' => null,
        ];
        $timerState['server_time_ms'] = floor(microtime(true) * 1000);

        $data = [
            'matchNumber' => $matchNumber,
            'merge' => $merge,
            'displayName' => $displayName,
            'currentRound' => $currentRound,
            'selectedPoolId' => $selectedPoolId,
            'registrations' => $registrations,
            'firstDrawing' => $firstDrawing,
            'availablePools' => $availablePools,
            'tiedIds' => $tiedIds,
            'activeDrawingId' => $activeDrawingId,
            'assignedArbitrase' => $assignedArbitrase,
            'assignedReferees' => $assignedReferees,
            'assignedKoordinators' => $assignedKoordinators,
            'assignedPaniteras' => $assignedPaniteras,
            'timerState' => $timerState,
            'courtId' => $courtId,
        ];

        return $this->stateCache->conditionalJson($request, $data, [
            'match' => $this->stateCache->version('match', $matchNumber->id),
            'court' => $courtId ? $this->stateCache->version('court', $courtId) : 1,
        ], 0);
    }

    public function embuCallOfficials(Request $request): JsonResponse
    {
        $matchId = $request->input('match_id');
        $currentRound = $request->input('round', 'Penyisihan');
        $selectedPoolId = $request->input('pool_id');

        $matchNumber = MatchNumber::findOrFail($matchId);

        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $matchNumber->id)
            ->first();

        $mergeName = null;
        if ($mergeDetails) {
            $merge = MatchNumberMerge::find($mergeDetails->match_number_merge_id);
            $mergeName = $merge?->name;
            $matchNumberIds = DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        } else {
            $matchNumberIds = [$matchNumber->id];
        }

        $drawingsQuery = DrawingMatchNumber::with(['court', 'pool', 'sessionTime', 'registration.contingent'])
            ->whereIn('match_number_id', $matchNumberIds)
            ->where('round', $currentRound);

        if ($currentRound === 'Penyisihan' && $selectedPoolId) {
            $drawingsQuery->where('pool_id', $selectedPoolId);
        }

        $drawings = $drawingsQuery->get();
        $firstDrawing = $drawings->first();

        if ($firstDrawing) {
            $matchName = $mergeName ?? $matchNumber->name;
            $courtName = $firstDrawing->court->name ?? 'Lapangan';
            $poolName = $firstDrawing->pool ? ' Pool '.$firstDrawing->pool->name : '';
            $sessionTime = $firstDrawing->sessionTime ? $firstDrawing->sessionTime->name : '';

            $intro = "Persiapan untuk pertandingan kategori {$matchName}{$poolName}, di {$courtName}, {$sessionTime}. ";

            // 1. Panggilan Kontingen
            $contingentNames = $drawings->pluck('registration.contingent.name')->unique()->filter()->values();
            $contingentCall = '';
            if ($contingentNames->isNotEmpty()) {
                $contingentCall = 'Kepada seluruh kontingen: '.$contingentNames->implode(', ').'. Silakan mempersiapkan atletnya. ';
            }

            // 2. Panggilan Wasit
            $refereeNames = ScheduleReferee::with('referee.user')
                ->where('court_id', $firstDrawing->court_id)
                ->where('session_time_id', $firstDrawing->session_time_id)
                ->get()
                ->pluck('referee.user.name')
                ->unique()
                ->filter()
                ->values();

            $refereeCall = '';
            if ($refereeNames->isNotEmpty()) {
                $refereeCall = 'Kepada para dewan juri dan wasit: '.$refereeNames->implode(', ').'. Mohon segera menempati posisi. ';
            }

            $outro = "Sekali lagi, panggilan untuk seluruh official dan kontingen pada kategori {$matchName}{$poolName}. Mohon segera menuju {$courtName}. Terima kasih.";

            $fullText = $intro.$contingentCall.$refereeCall.$outro;

            return response()->json([
                'success' => true,
                'announcement_text' => $fullText,
                'text' => 'Seluruh kontingen, juri, panitera, dan korlap telah dipanggil secara spesifik.',
            ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        return response()->json(['success' => false, 'message' => 'Drawing tidak ditemukan.'], 404)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function embuCallParticipant(EmbuCallParticipantRequest $request): JsonResponse
    {
        $drawingId = $request->input('drawing_id');
        $drawing = DrawingMatchNumber::with(['court', 'pool', 'registration.contingent', 'registration.athletes', 'matchNumber'])
            ->findOrFail($drawingId);

        if (! $drawing->registration) {
            return response()->json([
                'success' => false,
                'message' => 'Peserta lolos babak final belum digenerate.',
            ], 400)->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        $registrationId = $drawing->registration_id;

        $matchNumber = MatchNumber::findOrFail($drawing->match_number_id);
        $matchNumber->update(['active_registration_id' => $registrationId]);

        if ($drawing->court) {
            $drawing->court->update([
                'active_match_id' => $drawing->match_number_id,
                'active_drawing_id' => $drawing->id,
                'active_registration_id' => $registrationId,
                'active_bracket_node' => null,
            ]);

            Cache::put("court_{$drawing->court_id}_timer", [
                'status' => 'stopped',
                'elapsed_ms' => 0,
                'started_at_ms' => null,
            ]);

            $this->stateCache->bumpCourt($drawing->court_id);
            $this->stateCache->bumpMatch($drawing->match_number_id);

            $metaAthleteIds = $drawing->metadata['athlete_ids'] ?? [];
            if (! empty($metaAthleteIds)) {
                $athletes = Athlete::whereIn('id', $metaAthleteIds)->pluck('name')->implode(', ');
            } else {
                $athletes = Athlete::whereHas('matchNumbers', function ($q) use ($drawing) {
                    $q->where('match_numbers.id', $drawing->match_number_id)
                        ->where('athlete_match_number.registration_id', $drawing->registration_id);
                })->pluck('name')->implode(', ');
            }

            $contingent = $drawing->registration->contingent->name ?? '—';

            $mergeDetails = DB::table('match_number_merge_details')
                ->where('match_number_id', $drawing->match_number_id)
                ->first();
            $mergeName = null;
            if ($mergeDetails) {
                $merge = MatchNumberMerge::find($mergeDetails->match_number_merge_id);
                $mergeName = $merge?->name;
            }
            $matchName = $mergeName ?? $drawing->matchNumber->name;
            $courtName = $drawing->court->name;
            $poolName = $drawing->pool ? ' Pool '.$drawing->pool->name : '';

            $announcementText = "Panggilan untuk kontingen {$contingent}. Atas nama {$athletes}. Silakan menuju {$courtName}. Untuk kategori {$matchName}{$poolName}.";

            event(new CourtUpdated($drawing->court_id, null, 'court'));
            $mergeDetails = DB::table('match_number_merge_details')
                ->where('match_number_id', $drawing->match_number_id)
                ->first();
            $matchNumberIds = $mergeDetails ? DB::table('match_number_merge_details')->where('match_number_merge_id', $mergeDetails->match_number_merge_id)->pluck('match_number_id')->toArray() : [$drawing->match_number_id];
            foreach ($matchNumberIds as $id) {
                $this->stateCache->bumpMatch($id);
                event(new MatchUpdated($id, 'participant_called'));
            }

            return response()->json([
                'success' => true,
                'announcement_text' => $announcementText,
                'text' => 'Layar wasit dan TV Monitor kini terpusat ke peserta ini.',
            ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        return response()->json(['success' => false, 'message' => 'Lapangan tidak ditemukan pada drawing.'], 400)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function embuDismissParticipant(Request $request): JsonResponse
    {
        $matchId = $request->input('match_id');
        $courtId = $request->input('court_id');

        $matchNumber = MatchNumber::findOrFail($matchId);
        $matchNumber->update(['active_registration_id' => null]);

        if ($courtId) {
            Cache::put("court_{$courtId}_timer", [
                'status' => 'stopped',
                'elapsed_ms' => 0,
                'started_at_ms' => null,
            ]);

            $this->stateCache->bumpCourt($courtId);

            Court::where('id', $courtId)->update([
                'active_registration_id' => null,
                'active_drawing_id' => null,
                'active_match_id' => null,
                'active_bracket_node' => null,
            ]);
        }

        if ($courtId) {
            $this->stateCache->bumpCourt($courtId);
            event(new CourtUpdated($courtId, null, 'court'));
        }

        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $matchId)
            ->first();
        $matchNumberIds = $mergeDetails ? DB::table('match_number_merge_details')->where('match_number_merge_id', $mergeDetails->match_number_merge_id)->pluck('match_number_id')->toArray() : [$matchId];

        foreach ($matchNumberIds as $id) {
            $this->stateCache->bumpMatch($id);
            event(new MatchUpdated($id, 'participant_dismissed'));
        }

        return response()->json([
            'success' => true,
            'text' => 'Wasit, TV Monitor, dan Timer telah direset.',
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function embuFinishMatch(EmbuFinishMatchRequest $request): JsonResponse
    {
        $drawingId = $request->input('drawing_id');
        $timeMs = (float) $request->input('time_ms', 0);
        $round = $request->input('round', 'Penyisihan');

        $drawing = DrawingMatchNumber::findOrFail($drawingId);
        $registrationId = $drawing->registration_id;

        $courtId = $drawing->court_id;
        if ($courtId) {
            Cache::put("court_{$courtId}_timer", [
                'status' => 'stopped',
                'elapsed_ms' => 0,
                'started_at_ms' => null,
            ]);
        }

        // Apply Penalty automatically
        $seconds = floor($timeMs / 1000);
        $denda = 0;

        $matchNumber = MatchNumber::find($drawing->match_number_id);
        $isGroup = $matchNumber ? ($matchNumber->max_athletes > 1) : false;

        if ($isGroup) {
            if ($seconds >= 80 && $seconds <= 89) {
                $denda = 5;
            } elseif ($seconds <= 79) {
                $denda = 10;
            } elseif ($seconds >= 121 && $seconds <= 130) {
                $denda = 5;
            } elseif ($seconds >= 131) {
                $denda = 10;
            }
        } else {
            if ($seconds >= 50 && $seconds <= 59) {
                $denda = 5;
            } elseif ($seconds <= 49) {
                $denda = 10;
            } elseif ($seconds >= 91 && $seconds <= 100) {
                $denda = 5;
            } elseif ($seconds >= 101) {
                $denda = 10;
            }
        }

        $minutes = floor($seconds / 60);
        $remainingSeconds = $seconds % 60;
        $formattedTime = sprintf('%02d:%02d', $minutes, $remainingSeconds);

        $score = EmbuScore::firstOrCreate(
            [
                'match_number_id' => $drawing->match_number_id,
                'registration_id' => $registrationId,
                'round_label' => $round,
                'drawing_id' => $drawing->id,
            ],
            [
                'judge_1' => 0,
                'judge_2' => 0,
                'judge_3' => 0,
                'judge_4' => 0,
                'judge_5' => 0,
                'denda' => 0,
                'total_score' => 0,
                'nilai_akhir' => 0,
            ]
        );

        $score->denda = $denda;
        $score->waktu = $formattedTime;

        $judges = [
            $score->judge_1,
            $score->judge_2,
            $score->judge_3,
            $score->judge_4,
            $score->judge_5,
        ];

        $scoredCount = count(array_filter($judges, fn ($v) => $v > 0));

        if ($scoredCount === 5) {
            sort($judges);
            $total = $judges[1] + $judges[2] + $judges[3];
        } else {
            $total = array_sum($judges);
        }

        $score->total_score = $total;
        $score->nilai_akhir = max(0, $total - $score->denda);
        $score->save();

        if ($matchNumber) {
            $matchNumber->update(['active_registration_id' => null]);
        }

        if ($courtId) {
            $this->stateCache->bumpCourt($courtId);
            event(new CourtUpdated($courtId, null, 'court'));
        }
        event(new MatchUpdated($drawing->match_number_id, 'match_finished'));
        $this->stateCache->bumpMatch($drawing->match_number_id);

        return response()->json([
            'success' => true,
            'denda' => $denda,
            'seconds' => $seconds,
            'text' => 'Waktu dan denda telah diakumulasi, Panggilan ditutup.',
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function embuSaveScore(EmbuSaveScoreRequest $request): JsonResponse
    {
        $matchId = $request->input('match_id');
        $registrationId = $request->input('registration_id');
        $round = $request->input('round', 'Penyisihan');
        $scoresInput = $request->input('scores', []);
        $denda = (float) $request->input('denda', 0);

        $matchNumber = MatchNumber::findOrFail($matchId);

        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $matchNumber->id)
            ->first();

        if ($mergeDetails) {
            $matchNumberIds = DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        } else {
            $matchNumberIds = [$matchNumber->id];
        }

        $judgeValues = [
            (float) ($scoresInput['judge_1'] ?? 0),
            (float) ($scoresInput['judge_2'] ?? 0),
            (float) ($scoresInput['judge_3'] ?? 0),
            (float) ($scoresInput['judge_4'] ?? 0),
            (float) ($scoresInput['judge_5'] ?? 0),
        ];

        $scoredCount = count(array_filter($judgeValues, fn ($v) => $v > 0));

        if ($scoredCount === 5) {
            sort($judgeValues);
            $total = $judgeValues[1] + $judgeValues[2] + $judgeValues[3];
        } else {
            $total = array_sum($judgeValues);
        }

        $nilaiAkhir = max(0, $total - $denda);

        $drawingId = $request->input('drawing_id');
        if ($drawingId) {
            $drawing = DrawingMatchNumber::find($drawingId);
        } else {
            $drawing = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)
                ->where('registration_id', $registrationId)
                ->where('round', $round)
                ->first();
        }

        $score = EmbuScore::updateOrCreate(
            [
                'match_number_id' => $drawing ? $drawing->match_number_id : $matchNumber->id,
                'registration_id' => $registrationId,
                'round_label' => $round,
                'drawing_id' => $drawing ? $drawing->id : null,
                'tiebreak_round' => 0,
            ],
            [
                'judge_1' => (float) ($scoresInput['judge_1'] ?? 0),
                'judge_2' => (float) ($scoresInput['judge_2'] ?? 0),
                'judge_3' => (float) ($scoresInput['judge_3'] ?? 0),
                'judge_4' => (float) ($scoresInput['judge_4'] ?? 0),
                'judge_5' => (float) ($scoresInput['judge_5'] ?? 0),
                'total_score' => $total,
                'denda' => $denda,
                'nilai_akhir' => $nilaiAkhir,
            ]
        );

        $this->bracketService->recalculateRanks($matchNumberIds, $round);

        if ($drawing && $drawing->court_id) {
            $this->stateCache->bumpCourt($drawing->court_id);
            event(new CourtUpdated($drawing->court_id, null, 'court'));
        }
        foreach ($matchNumberIds as $id) {
            $this->stateCache->bumpMatch($id);
            event(new MatchUpdated($id, 'score_saved'));
        }

        return response()->json([
            'success' => true,
            'text' => 'Nilai Berhasil Disimpan. Total: '.number_format($total, 1).' | Nilai Akhir: '.number_format($nilaiAkhir, 1),
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function embuRequestTiebreak(EmbuRequestTiebreakRequest $request): JsonResponse
    {
        $matchId = $request->input('match_id');
        $registrationIds = $request->input('registration_ids', []);
        $round = $request->input('round', 'Penyisihan');

        $matchNumber = MatchNumber::findOrFail($matchId);

        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $matchNumber->id)
            ->first();

        if ($mergeDetails) {
            $matchNumberIds = DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        } else {
            $matchNumberIds = [$matchNumber->id];
        }

        foreach ($registrationIds as $regId) {
            $drawing = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)
                ->where('registration_id', $regId)
                ->where('round', $round)
                ->first();

            $targetMatchId = $drawing ? $drawing->match_number_id : $matchNumber->id;

            $lastScore = EmbuScore::where('match_number_id', $targetMatchId)
                ->where('registration_id', $regId)
                ->where('round_label', $round)
                ->orderByDesc('tiebreak_round')
                ->first();

            $nextTiebreak = ($lastScore->tiebreak_round ?? 0) + 1;

            EmbuScore::create([
                'match_number_id' => $targetMatchId,
                'registration_id' => $regId,
                'round_label' => $round,
                'drawing_id' => $drawing ? $drawing->id : null,
                'judge_1' => 0,
                'judge_2' => 0,
                'judge_3' => 0,
                'judge_4' => 0,
                'judge_5' => 0,
                'total_score' => 0,
                'nilai_akhir' => 0,
                'denda' => 0,
                'tiebreak_round' => $nextTiebreak,
            ]);
        }

        $drawing = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)->first();
        if ($drawing && $drawing->court_id) {
            $this->stateCache->bumpCourt($drawing->court_id);
            event(new CourtUpdated($drawing->court_id, null, 'court'));
        }
        foreach ($matchNumberIds as $id) {
            $this->stateCache->bumpMatch($id);
            event(new MatchUpdated($id, 'tiebreak_requested'));
        }

        return response()->json([
            'success' => true,
            'text' => count($registrationIds).' peserta akan mengulangi penilaian.',
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function embuAdvanceToFinal(EmbuAdvanceToFinalRequest $request): JsonResponse
    {
        $matchId = $request->input('match_id');
        $threshold = $request->input('threshold');

        $matchNumber = MatchNumber::findOrFail($matchId);

        $mergeDetails = DB::table('match_number_merge_details')
            ->where('match_number_id', $matchNumber->id)
            ->first();

        if ($mergeDetails) {
            $matchNumberIds = DB::table('match_number_merge_details')
                ->where('match_number_merge_id', $mergeDetails->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        } else {
            $matchNumberIds = [$matchNumber->id];
        }

        $limitPerPool = $threshold;
        if (is_null($limitPerPool)) {
            $drawingData = $matchNumber->drawing_data;
            if ($drawingData && isset($drawingData['qualifiers'])) {
                $limitPerPool = (int) $drawingData['qualifiers'];
            } else {
                $limitPerPool = 4;
            }
        }

        $penyisihanDrawings = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)
            ->where('round', 'Penyisihan')
            ->get();

        $byPool = $penyisihanDrawings->groupBy('pool_id');
        $qualifiers = collect();
        $ties = [];

        foreach ($byPool as $poolId => $drawingsInPool) {
            $regIdsInPool = $drawingsInPool->pluck('registration_id');

            $scoresInPool = EmbuScore::whereIn('match_number_id', $matchNumberIds)
                ->where('round_label', 'Penyisihan')
                ->where('tiebreak_round', 0)
                ->whereIn('registration_id', $regIdsInPool)
                ->orderBy('nilai_akhir')
                ->get();

            $topInPool = $scoresInPool->take($limitPerPool);
            $qualifiers = $qualifiers->concat($topInPool);

            if ($scoresInPool->count() > $limitPerPool) {
                $boundaryScore = $scoresInPool->get($limitPerPool - 1)?->nilai_akhir;
                $tiedInPool = $scoresInPool->filter(fn ($s) => (float) $s->nilai_akhir === (float) $boundaryScore);

                if ($tiedInPool->count() > 1 && $tiedInPool->last()->nilai_akhir == $scoresInPool->get($limitPerPool)?->nilai_akhir) {
                    $ties = array_merge($ties, $tiedInPool->pluck('registration_id')->toArray());
                }
            }
        }

        if ($qualifiers->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Belum ada nilai penyisihan'], 400)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        if (! empty($ties)) {
            return response()->json([
                'success' => false,
                'message' => 'Terdapat '.count($ties).' peserta dengan nilai yang sama. Lakukan Tanding Ulang terlebih dahulu.',
            ], 400)->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        $firstDrawing = $penyisihanDrawings->first();
        $seq = 1;
        $shuffledQualifiers = $qualifiers->shuffle();

        foreach ($shuffledQualifiers as $score) {
            DrawingMatchNumber::updateOrCreate(
                [
                    'match_number_id' => $score->match_number_id ?? $matchNumber->id,
                    'registration_id' => $score->registration_id,
                    'round' => 'Final',
                ],
                [
                    'draft_type' => $matchNumber->draft_type,
                    'court_id' => $firstDrawing?->court_id,
                    'session_time_id' => $firstDrawing?->session_time_id,
                    'rundown_id' => $firstDrawing?->rundown_id,
                    'pool_id' => $firstDrawing?->pool_id,
                    'sequence_number' => $seq++,
                ]
            );
        }

        $drawing = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)->first();
        if ($drawing && $drawing->court_id) {
            $this->stateCache->bumpCourt($drawing->court_id);
            event(new CourtUpdated($drawing->court_id, null, 'court'));
        }
        foreach ($matchNumberIds as $id) {
            $this->stateCache->bumpMatch($id);
            event(new MatchUpdated($id, 'advanced_to_final'));
        }

        return response()->json([
            'success' => true,
            'text' => $qualifiers->count().' peserta berhasil lolos ke babak Final.',
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
}
