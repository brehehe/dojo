<?php

namespace App\Http\Controllers;

use App\Events\CourtUpdated;
use App\Events\MatchUpdated;
use App\Http\Requests\RandoriCallMatchRequest;
use App\Http\Requests\RandoriConfirmChampionRequest;
use App\Http\Requests\RandoriSubmitScoringRequest;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\MatchNumber\MatchNumber;
use App\Models\MatchNumberMerge;
use App\Models\RandoriMatchResult;
use App\Models\SchedulePanitera;
use App\Models\ScheduleReferee;
use App\Models\TournamentResult;
use App\Services\BracketService;
use App\Services\StateCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RandoriScoringController extends Controller
{
    public function __construct(
        protected BracketService $bracketService,
        protected StateCache $stateCache,
    ) {}

    public function scoringRandoriState(Request $request, MatchNumber $matchNumber): JsonResponse
    {
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

        $drawingData = $matchNumber->drawing_data ?? [];

        // Migrate legacy single-elimination to double_elimination if needed
        if (! isset($drawingData['bracket_type']) || $drawingData['bracket_type'] !== 'double_elimination') {
            $drawingData = $this->bracketService->migrateLegacyBracket($drawingData);
            if ($drawingData) {
                $matchNumber->update(['drawing_data' => $drawingData]);
            }
        }

        if ($merge) {
            $mergedNames = MatchNumber::whereIn('id', $matchNumberIds)->pluck('name')->join(', ');
            $displayName = ($merge->name ?: 'Merged Group').' ('.$mergedNames.')';
        } else {
            $displayName = $matchNumber->name;
        }

        $firstDrawing = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)->first();
        $officials = $firstDrawing?->metadata['officials'] ?? null;

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

        // Fetch saved tournament results (champions)
        $savedResults = TournamentResult::whereIn('match_number_id', $matchNumberIds)
            ->orderBy('rank')
            ->get();

        $juaraMap = [];
        foreach ($savedResults as $res) {
            $juaraMap[$res->rank] = [
                'name' => $res->athlete_names,
                'contingent' => $res->contingent_name,
                'registration_id' => $res->registration_id,
            ];
        }

        // Always fallback to drawingData['juara'] for missing ranks (like rank 3 and 4 in single elimination)
        $drawingJuara = $drawingData['juara'] ?? [];
        foreach ($drawingJuara as $rank => $athlete) {
            if (! isset($juaraMap[$rank])) {
                $juaraMap[$rank] = $athlete;
            }
        }

        if (empty($juaraMap)) {
            $gf = $drawingData['grand_final'] ?? null;
            if ($gf && ($gf['winner'] ?? null)) {
                $juaraMap[1] = $gf['winner_data'];
                $juaraMap[2] = ($gf['winner'] === 'athlete1') ? $gf['athlete2'] : $gf['athlete1'];
            }
        }

        $courtId = $firstDrawing?->court_id;
        $timerState = $courtId ? Cache::get("court_{$courtId}_timer", [
            'status' => 'stopped',
            'elapsed_ms' => 0,
            'started_at_ms' => null,
        ]) : [
            'status' => 'stopped',
            'elapsed_ms' => 0,
            'started_at_ms' => null,
        ];
        $timerState['server_time_ms'] = floor(microtime(true) * 1000);

        // Fetch randori results
        $randoriResults = RandoriMatchResult::whereIn('match_number_id', $matchNumberIds)->get()->keyBy('bracket_node');

        $data = [
            'matchNumber' => $matchNumber,
            'merge' => $merge,
            'displayName' => $displayName,
            'drawingData' => $drawingData,
            'officials' => $officials,
            'assignedArbitrase' => $assignedArbitrase,
            'assignedReferees' => $assignedReferees,
            'assignedKoordinators' => $assignedKoordinators,
            'assignedPaniteras' => $assignedPaniteras,
            'juara' => (object) $juaraMap,
            'timerState' => $timerState,
            'courtId' => $courtId,
            'randoriResults' => $randoriResults,
            'activeBracketNode' => $matchNumber->active_bracket_node,
        ];

        return $this->stateCache->conditionalJson($request, $data, [
            'match' => $this->stateCache->version('match', $matchNumber->id),
            'court' => $courtId ? $this->stateCache->version('court', $courtId) : 1,
        ], 0);
    }

    public function randoriRepairBracket(Request $request): JsonResponse
    {
        $matchId = $request->input('match_id');
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

        $data = $matchNumber->fresh()->drawing_data ?? [];

        if (empty($data['upper_bracket']['rounds'])) {
            return response()->json(['success' => false, 'message' => 'UB rounds empty.'], 400)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        $ubRounds = $data['upper_bracket']['rounds'] ?? [];
        $lbRounds = $data['lower_bracket']['rounds'] ?? [];
        $totalUB = count($ubRounds);
        $totalLB = count($lbRounds);
        $lbFinalIdx = $totalLB - 1;

        // 1. Fix UB round routing
        foreach ($ubRounds as $r => $matches) {
            $isUBFinal = ($r === $totalUB - 1);

            foreach ($matches as $m => $match) {
                if ($isUBFinal) {
                    $ubRounds[$r][$m]['winner_next'] = ['bracket' => 'gf', 'slot' => 'athlete1'];
                    $ubRounds[$r][$m]['loser_next'] = ['bracket' => 'lb', 'round' => $lbFinalIdx, 'match' => 0, 'slot' => 'athlete2'];
                } else {
                    $ubRounds[$r][$m]['winner_next'] = [
                        'bracket' => 'ub',
                        'round' => $r + 1,
                        'match' => (int) ($m / 2),
                        'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2',
                    ];

                    if ($r === 0) {
                        $ubRounds[$r][$m]['loser_next'] = [
                            'bracket' => 'lb',
                            'round' => 0,
                            'match' => (int) ($m / 2),
                            'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2',
                        ];
                    } else {
                        $ubRounds[$r][$m]['loser_next'] = [
                            'bracket' => 'lb',
                            'round' => 2 * $r - 1,
                            'match' => $m,
                            'slot' => 'athlete2',
                        ];
                    }
                }

                $ubRounds[$r][$m]['winner'] = $ubRounds[$r][$m]['winner'] ?? null;
                $ubRounds[$r][$m]['winner_data'] = $ubRounds[$r][$m]['winner_data'] ?? null;
            }
        }

        // 2. Fix LB round routing
        foreach ($lbRounds as $lr => $matches) {
            $isLBFinal = ($lr === $lbFinalIdx);
            $isLBSemi = ($lr === $lbFinalIdx - 1);

            foreach ($matches as $m => $match) {
                $lbRounds[$lr][$m]['winner'] = $lbRounds[$lr][$m]['winner'] ?? null;
                $lbRounds[$lr][$m]['winner_data'] = $lbRounds[$lr][$m]['winner_data'] ?? null;

                if ($isLBFinal) {
                    $lbRounds[$lr][$m]['winner_next'] = ['bracket' => 'gf', 'slot' => 'athlete2'];
                    $lbRounds[$lr][$m]['loser_next'] = ['bracket' => 'eliminated'];
                } elseif ($isLBSemi) {
                    $lbRounds[$lr][$m]['winner_next'] = ['bracket' => 'lb', 'round' => $lbFinalIdx, 'match' => 0, 'slot' => 'athlete1'];
                    $lbRounds[$lr][$m]['loser_next'] = ['bracket' => 'eliminated'];
                } elseif ($lr % 2 === 1) {
                    $lbRounds[$lr][$m]['winner_next'] = [
                        'bracket' => 'lb',
                        'round' => $lr + 1,
                        'match' => (int) ($m / 2),
                        'slot' => $m % 2 === 0 ? 'athlete1' : 'athlete2',
                    ];
                    $lbRounds[$lr][$m]['loser_next'] = ['bracket' => 'eliminated'];
                } else {
                    $lbRounds[$lr][$m]['winner_next'] = [
                        'bracket' => 'lb',
                        'round' => $lr + 1,
                        'match' => $m,
                        'slot' => 'athlete1',
                    ];
                    $lbRounds[$lr][$m]['loser_next'] = ['bracket' => 'eliminated'];
                }
            }
        }

        $data['upper_bracket']['rounds'] = $ubRounds;
        $data['lower_bracket']['rounds'] = $lbRounds;

        $results = RandoriMatchResult::whereIn('match_number_id', $matchNumberIds)
            ->orderBy('id')
            ->get();

        foreach ($results as $result) {
            $parts = explode('_', $result->bracket_node);
            if (count($parts) < 3) {
                continue;
            }

            $bracket = $parts[0];
            $roundIdx = (int) $parts[1];
            $matchIdx = (int) $parts[2];

            if ($bracket === 'ub') {
                $match = $data['upper_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
            } elseif ($bracket === 'lb') {
                $match = $data['lower_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
            } elseif ($bracket === 'gf') {
                $match = $data['grand_final'] ?? null;
            } else {
                continue;
            }

            if (! $match) {
                continue;
            }

            $winnerSlot = $result->winner_color;
            $loserSlot = $winnerSlot === 'athlete1' ? 'athlete2' : 'athlete1';

            $winnerData = $match[$winnerSlot] ?? null;
            $loserData = $match[$loserSlot] ?? null;

            if (! $winnerData) {
                continue;
            }

            $match['winner'] = $winnerSlot;
            $match['winner_data'] = $winnerData;

            if ($bracket === 'ub') {
                $data['upper_bracket']['rounds'][$roundIdx][$matchIdx] = $match;
            } elseif ($bracket === 'lb') {
                $data['lower_bracket']['rounds'][$roundIdx][$matchIdx] = $match;
            } elseif ($bracket === 'gf') {
                $data['grand_final'] = $match;
            }

            if ($match['winner_next'] ?? null) {
                $data = $this->bracketService->placeAthlete($data, $match['winner_next'], $winnerData, $matchNumberIds);
            }

            if ($loserData && ($match['loser_next'] ?? null)) {
                $lb = $match['loser_next']['bracket'] ?? 'eliminated';
                if ($lb === 'lb') {
                    $data = $this->bracketService->placeAthlete($data, $match['loser_next'], $loserData, $matchNumberIds);
                } elseif ($lb === 'ranked') {
                    $data['juara'][$match['loser_next']['rank']] = $loserData;
                }
            }

            if ($bracket === 'gf') {
                $data['juara'][1] = $winnerData;
                $data['juara'][2] = $loserData;
            }
        }

        $data = $this->bracketService->propagateBracketByes($data, $matchNumberIds);

        $matchNumber->update(['drawing_data' => $data]);

        foreach ($matchNumberIds as $id) {
            $this->stateCache->bumpMatch($id);
            event(new MatchUpdated($id, 'bracket'));
        }

        return response()->json([
            'success' => true,
            'text' => 'Routing diperbaiki & '.count($results).' hasil di-replay ulang.',
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function randoriCallOfficials(Request $request): JsonResponse
    {
        $matchId = $request->input('match_id');
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

        $drawings = DrawingMatchNumber::with(['court', 'sessionTime', 'registration.contingent'])
            ->whereIn('match_number_id', $matchNumberIds)
            ->get();

        $firstDrawing = $drawings->first();

        if ($firstDrawing) {
            $matchName = $matchNumber->name;
            $courtName = $firstDrawing->court->name ?? 'Lapangan';
            $sessionTime = $firstDrawing->sessionTime ? $firstDrawing->sessionTime->name : '';

            $intro = "Persiapan untuk pertandingan kategori {$matchName}, di {$courtName}, {$sessionTime}. ";

            $contingentNames = $drawings->pluck('registration.contingent.name')->unique()->filter()->values();
            $contingentCall = '';
            if ($contingentNames->isNotEmpty()) {
                $contingentCall = 'Kepada seluruh kontingen: '.$contingentNames->implode(', ').'. Silakan mempersiapkan atletnya. ';
            }

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

            $officials = $firstDrawing->metadata['officials'] ?? null;
            $officialCall = '';
            if ($officials) {
                $paniteras = is_array($officials['panitera'] ?? null) ? implode(', ', $officials['panitera']) : ($officials['panitera'] ?? '');
                $korlap = $officials['koordinator_lapangan'] ?? '';

                if ($paniteras) {
                    $officialCall .= "Kepada petugas panitera: {$paniteras}. ";
                }
                if ($korlap) {
                    $officialCall .= "Kepada koordinator lapangan: {$korlap}. ";
                }
            }

            $outro = "Sekali lagi, panggilan untuk seluruh official dan kontingen pada kategori {$matchName}. Mohon segera menuju {$courtName}. Terima kasih.";

            $fullText = $intro.$contingentCall.$refereeCall.$officialCall.$outro;

            return response()->json([
                'success' => true,
                'announcement_text' => $fullText,
                'text' => 'Panggilan Detail Dilakukan.',
            ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        return response()->json(['success' => false, 'message' => 'Drawing tidak ditemukan.'], 404)
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function randoriCallMatch(RandoriCallMatchRequest $request): JsonResponse
    {
        $matchId = $request->input('match_id');
        $nodeKey = $request->input('node_key');
        $roundIdx = (int) $request->input('round_idx');
        $matchIdx = (int) $request->input('match_idx');
        $bracket = $request->input('bracket');

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

        MatchNumber::whereIn('id', $matchNumberIds)->update(['active_bracket_node' => $nodeKey]);

        $drawing = DrawingMatchNumber::with('court')
            ->whereIn('match_number_id', $matchNumberIds)
            ->first();

        if ($drawing && $drawing->court_id) {
            $drawing->court->update([
                'active_match_id' => $drawing->match_number_id,
                'active_drawing_id' => $drawing->id,
                'active_registration_id' => null,
                'active_bracket_node' => $nodeKey,
            ]);

            Cache::put("court_{$drawing->court_id}_timer", [
                'status' => 'stopped',
                'elapsed_ms' => 0,
                'started_at_ms' => null,
            ]);

            $this->stateCache->bumpCourt($drawing->court_id);

            event(new CourtUpdated($drawing->court_id, null, 'court'));
            foreach ($matchNumberIds as $id) {
                $this->stateCache->bumpMatch($id);
                event(new MatchUpdated($id, 'match_called'));
            }

            $drawingData = $matchNumber->fresh()->drawing_data ?? [];
            $targetBracket = $bracket === 'ub' ? 'upper_bracket' : ($bracket === 'lb' ? 'lower_bracket' : $bracket);
            $match = $drawingData[$targetBracket]['rounds'][$roundIdx][$matchIdx] ?? null;

            if ($match) {
                $a1 = $match['athlete1']['name'] ?? 'Menunggu';
                $c1 = $match['athlete1']['contingent'] ?? '';
                $a2 = $match['athlete2']['name'] ?? 'Menunggu';
                $c2 = $match['athlete2']['contingent'] ?? '';
                $info = strtoupper($targetBracket).' Round '.($roundIdx + 1);
                $announcementText = "Pertandingan selanjutnya: {$info}. Di sudut Merah, {$a1} dari {$c1}. Di sudut Putih, {$a2} dari {$c2}. Mohon segera bersiap.";

                return response()->json([
                    'success' => true,
                    'announcement_text' => $announcementText,
                    'text' => 'Layar wasit dan TV Monitor kini terpusat ke pertandingan ini.',
                ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
            }
        }

        return response()->json(['success' => true, 'text' => 'Pertandingan dipanggil.'])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function randoriCallGrandFinal(Request $request): JsonResponse
    {
        $matchId = $request->input('match_id');
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

        MatchNumber::whereIn('id', $matchNumberIds)->update(['active_bracket_node' => 'gf_0_0']);

        $drawing = DrawingMatchNumber::with('court')
            ->whereIn('match_number_id', $matchNumberIds)
            ->first();

        if ($drawing && $drawing->court_id) {
            $drawing->court->update([
                'active_match_id' => $drawing->match_number_id,
                'active_drawing_id' => $drawing->id,
                'active_registration_id' => null,
                'active_bracket_node' => 'gf_0_0',
            ]);

            Cache::put("court_{$drawing->court_id}_timer", [
                'status' => 'stopped',
                'elapsed_ms' => 0,
                'started_at_ms' => null,
            ]);

            $this->stateCache->bumpCourt($drawing->court_id);

            event(new CourtUpdated($drawing->court_id, null, 'court'));
            foreach ($matchNumberIds as $id) {
                $this->stateCache->bumpMatch($id);
                event(new MatchUpdated($id, 'match_called'));
            }

            $drawingData = $matchNumber->fresh()->drawing_data ?? [];
            $match = $drawingData['grand_final'] ?? null;
            if ($match) {
                $a1 = $match['athlete1']['name'] ?? 'Menunggu';
                $c1 = $match['athlete1']['contingent'] ?? '';
                $a2 = $match['athlete2']['name'] ?? 'Menunggu';
                $c2 = $match['athlete2']['contingent'] ?? '';
                $announcementText = "Pertandingan selanjutnya: GRAND FINAL. Di sudut Merah, {$a1} dari {$c1}. Di sudut Putih, {$a2} dari {$c2}. Mohon segera bersiap.";

                return response()->json([
                    'success' => true,
                    'announcement_text' => $announcementText,
                    'text' => 'Layar wasit dan TV Monitor kini terpusat ke pertandingan GRAND FINAL.',
                ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
            }
        }

        return response()->json(['success' => true, 'text' => 'Grand Final dipanggil.'])
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function randoriDismissMatch(Request $request): JsonResponse
    {
        $matchId = $request->input('match_id');
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

        $drawing = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)->first();
        if ($drawing && $drawing->court_id) {
            Cache::put("court_{$drawing->court_id}_timer", ['status' => 'stopped', 'elapsed_ms' => 0, 'started_at_ms' => null]);
            $drawing->court->update(['active_match_id' => null, 'active_drawing_id' => null, 'active_registration_id' => null, 'active_bracket_node' => null]);
            $this->stateCache->bumpCourt($drawing->court_id);
            event(new CourtUpdated($drawing->court_id, null, 'court'));
        }

        MatchNumber::whereIn('id', $matchNumberIds)->update(['active_bracket_node' => null]);

        foreach ($matchNumberIds as $id) {
            $this->stateCache->bumpMatch($id);
            event(new MatchUpdated($id, 'match_dismissed'));
        }

        return response()->json([
            'success' => true,
            'text' => 'Panggilan ditutup, layar wasit dan TV Monitor telah direset.',
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function randoriSubmitScoring(RandoriSubmitScoringRequest $request): JsonResponse
    {
        $matchId = $request->input('match_id');
        $bracket = $request->input('bracket');
        $roundIdx = (int) $request->input('round');
        $matchIdx = (int) $request->input('match');

        $scoreRed = (int) $request->input('score_red');
        $scoreBlue = (int) $request->input('score_blue');

        $scoringAka = $request->input('scoring_aka');
        $scoringShiro = $request->input('scoring_shiro');

        $signatures = $request->input('signatures');

        if (empty($signatures['arbitrase']['name']) || empty($signatures['arbitrase']['signature'])) {
            return response()->json(['success' => false, 'message' => 'Nama dan Tanda tangan Arbitrase wajib diisi.'], 400)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }
        if (empty($signatures['koordinator']['name']) || empty($signatures['koordinator']['signature'])) {
            return response()->json(['success' => false, 'message' => 'Nama dan Tanda tangan Koordinator wajib diisi.'], 400)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }
        if (empty($signatures['wasit']['name']) || empty($signatures['wasit']['signature'])) {
            return response()->json(['success' => false, 'message' => 'Nama dan Tanda tangan Wasit wajib diisi.'], 400)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }
        foreach ($signatures['panitera'] as $idx => $p) {
            if (empty($p['name']) || empty($p['signature'])) {
                return response()->json(['success' => false, 'message' => 'Nama dan Tanda tangan Panitera ke-'.($idx + 1).' wajib diisi.'], 400)
                    ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
            }
        }
        if (empty($signatures['manager_red']['name']) || empty($signatures['manager_red']['signature'])) {
            return response()->json(['success' => false, 'message' => 'Nama dan Tanda tangan Manajer Pita Merah wajib diisi.'], 400)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }
        if (empty($signatures['manager_white']['name']) || empty($signatures['manager_white']['signature'])) {
            return response()->json(['success' => false, 'message' => 'Nama dan Tanda tangan Manajer Pita Putih wajib diisi.'], 400)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        if ($scoreRed === $scoreBlue) {
            return response()->json(['success' => false, 'message' => 'Poin sama (Hikiwake). Silakan tentukan pemenang lewat yusei_kachi atau mujoken_kachi.'], 400)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        $winnerSlot = $scoreRed > $scoreBlue ? 'athlete1' : 'athlete2';

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

        $data = $matchNumber->drawing_data;
        if ($bracket === 'ub') {
            $match = $data['upper_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
        } elseif ($bracket === 'lb') {
            $match = $data['lower_bracket']['rounds'][$roundIdx][$matchIdx] ?? null;
        } elseif ($bracket === 'gf') {
            $match = $data['grand_final'] ?? null;
        } else {
            return response()->json(['success' => false, 'message' => 'Bracket tidak valid.'], 400)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        if (! $match) {
            return response()->json(['success' => false, 'message' => 'Match tidak ditemukan.'], 404)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        $winnerData = $match[$winnerSlot] ?? null;
        $loserSlot = $winnerSlot === 'athlete1' ? 'athlete2' : 'athlete1';
        $loserData = $match[$loserSlot] ?? null;

        if (! $winnerData) {
            return response()->json(['success' => false, 'message' => 'Winner data empty.'], 400)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        $match['winner'] = $winnerSlot;
        $match['winner_data'] = $winnerData;

        if ($bracket === 'ub') {
            $data['upper_bracket']['rounds'][$roundIdx][$matchIdx] = $match;
        } elseif ($bracket === 'lb') {
            $data['lower_bracket']['rounds'][$roundIdx][$matchIdx] = $match;
        } elseif ($bracket === 'gf') {
            $data['grand_final'] = $match;
        }

        if ($match['winner_next'] ?? null) {
            if ($match['winner_next']['bracket'] === 'ranked') {
                $data['juara'][$match['winner_next']['rank']] = $winnerData;
            } else {
                $data = $this->bracketService->placeAthlete($data, $match['winner_next'], $winnerData, $matchNumberIds);
            }
        }

        if ($loserData && ($match['loser_next'] ?? null)) {
            if ($match['loser_next']['bracket'] === 'lb') {
                $data = $this->bracketService->placeAthlete($data, $match['loser_next'], $loserData, $matchNumberIds);
            } elseif ($match['loser_next']['bracket'] === 'ranked') {
                $data['juara'][$match['loser_next']['rank']] = $loserData;
            }
        }

        if ($bracket === 'gf') {
            $data['juara'][1] = $winnerData;
            $data['juara'][2] = $loserData;
        }

        $data = $this->bracketService->propagateBracketByes($data, $matchNumberIds);

        $targetMatchId = $winnerData['match_number_id'] ?? $matchNumber->id;

        RandoriMatchResult::updateOrCreate(
            ['match_number_id' => $targetMatchId, 'bracket_node' => $bracket.'_'.$roundIdx.'_'.$matchIdx],
            [
                'bracket_node_index' => $roundIdx.'_'.$matchIdx,
                'bracket_section' => $bracket,
                'winner_color' => $winnerSlot,
                'score_red' => $scoreRed,
                'score_blue' => $scoreBlue,
                'metadata' => json_encode([
                    'scoringAka' => $scoringAka,
                    'scoringShiro' => $scoringShiro,
                    'signatures' => $signatures,
                ]),
            ]
        );

        MatchNumber::whereIn('id', $matchNumberIds)->update(['drawing_data' => $data, 'active_bracket_node' => null]);

        $drawing = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)->first();
        if ($drawing && $drawing->court_id) {
            Cache::put("court_{$drawing->court_id}_timer", ['status' => 'stopped', 'elapsed_ms' => 0, 'started_at_ms' => null]);
            $drawing->court->update(['active_match_id' => null, 'active_drawing_id' => null, 'active_registration_id' => null, 'active_bracket_node' => null]);
            $this->stateCache->bumpCourt($drawing->court_id);
            event(new CourtUpdated($drawing->court_id, null, 'court'));
        }

        foreach ($matchNumberIds as $id) {
            $this->stateCache->bumpMatch($id);
            event(new MatchUpdated($id, 'score_submitted'));
        }

        return response()->json([
            'success' => true,
            'text' => 'Pemenang dicatat & bracket terupdate.',
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function randoriConfirmChampion(RandoriConfirmChampionRequest $request): JsonResponse
    {
        $matchId = $request->input('match_id');
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

        $data = $matchNumber->drawing_data;
        $juara = $data['juara'] ?? [];

        if (empty($juara)) {
            $gf = $data['grand_final'] ?? null;
            if ($gf && ($gf['winner'] ?? null)) {
                $juara[1] = $gf['winner_data'];
                $juara[2] = ($gf['winner'] === 'athlete1') ? $gf['athlete2'] : $gf['athlete1'];
            }
        }

        if (empty($juara)) {
            return response()->json(['success' => false, 'message' => 'Tentukan pemenang Grand Final terlebih dahulu.'], 400)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
        }

        $data['juara'] = $juara;
        $matchNumber->update(['drawing_data' => $data]);

        TournamentResult::whereIn('match_number_id', $matchNumberIds)->delete();

        foreach ($juara as $rank => $athlete) {
            if ($rank > 4) {
                continue;
            }

            if (! $athlete || ! isset($athlete['id']) || $athlete['id'] === 'BYE') {
                continue;
            }

            TournamentResult::updateOrCreate(
                [
                    'match_number_id' => $athlete['match_number_id'] ?? $matchNumber->id,
                    'registration_id' => $athlete['registration_id'] ?? null,
                ],
                [
                    'draft_type' => $matchNumber->draft_type,
                    'rank' => (int) $rank,
                    'athlete_names' => $athlete['name'] ?? '',
                    'contingent_name' => $athlete['contingent'] ?? '',
                    'category_id' => $matchNumber->age_group_id,
                    'generated_by' => auth()->user()?->name ?? 'Admin',
                    'confirmed_at' => now(),
                    'accumulated_score' => 0,
                    'penyisihan_score' => 0,
                    'final_score' => 0,
                ]
            );
        }

        foreach ($matchNumberIds as $id) {
            $this->stateCache->bumpMatch($id);
            event(new MatchUpdated($id, 'champions_confirmed'));
        }

        $courtId = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)
            ->whereNotNull('court_id')
            ->value('court_id')
            ?? Court::whereIn('active_match_id', $matchNumberIds)->value('id');

        if ($courtId) {
            $this->stateCache->bumpCourt($courtId);
            event(new CourtUpdated($courtId, null, 'court'));
        }

        return response()->json([
            'success' => true,
            'text' => 'Daftar juara telah berhasil dicatat ke sistem.',
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
}
