<?php

namespace App\Http\Controllers;

use App\Models\ActiveCourtReferee;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\MatchNumberMergeDetail;
use App\Models\RandoriMatchResult;
use App\Models\Registration;
use App\Models\ScheduleReferee;
use App\Services\BracketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Inertia\Response;

class MonitorController extends Controller
{
    public function __construct(
        protected BracketService $bracketService,
    ) {}

    // --- Inertia Page Renders ---

    public function monitorCourt(Court $court): Response
    {
        return Inertia::render('MonitorCourt', [
            'courtId' => $court->id,
        ]);
    }

    public function monitorHasilCourt(Court $court): Response
    {
        return Inertia::render('MonitorHasil', [
            'courtId' => $court->id,
        ]);
    }

    public function monitorHasilMatch(MatchNumber $match): Response
    {
        return Inertia::render('MonitorHasil', [
            'matchId' => $match->id,
        ]);
    }

    public function monitorReferee(Court $court): Response
    {
        return Inertia::render('MonitorReferee', [
            'courtId' => $court->id,
        ]);
    }

    public function monitorRekapitulasiHasil(Court $court): Response
    {
        return Inertia::render('MonitorRekapitulasiHasil', [
            'courtId' => $court->id,
        ]);
    }

    public function monitorTimer(Court $court): Response
    {
        return Inertia::render('MonitorTimer', [
            'courtId' => $court->id,
        ]);
    }

    public function monitorTimerStandalone(): Response
    {
        return Inertia::render('MonitorTimerStandalone');
    }

    // --- State API Endpoints for Polling ---

    public function monitorCourtState(Court $court): JsonResponse
    {
        $cacheKey = "monitor_court_state_{$court->id}";
        $data = Cache::remember($cacheKey, 3, function () use ($court) {
            $court->load([
                'activeMatch.athletes.registrations.contingent',
                'activeMatch.drawings',
                'activeMatch.ageGroup',
                'activeDrawing.matchNumber.ageGroup',
                'activeDrawing.matchNumber.athletes.registrations.contingent',
                'activeDrawing.registration.athletes',
                'activeDrawing.registration.contingent',
                'activeDrawing.pool',
                'activeDrawing.sessionTime',
                'activeDrawing.rundown',
                'activeDrawing.court',
            ]);

            $timerState = Cache::get("court_{$court->id}_timer", [
                'status' => 'stopped',
                'elapsed_ms' => 0,
                'started_at_ms' => null,
                'countdown_end_ms' => null,
            ]);
            $timerState['server_time_ms'] = floor(microtime(true) * 1000);

            return [
                'court' => $court,
                'timer_state' => $timerState,
            ];
        });

        $etag = md5(json_encode($data));

        return response()->json($data)
            ->header('Cache-Control', 'max-age=3, must-revalidate')
            ->header('ETag', $etag);
    }

    public function monitorHasilCourtState(Request $request, Court $court): JsonResponse
    {
        $cacheKey = "monitor_hasil_court_state_{$court->id}_{$request->query('round', '')}_{$request->query('pool_id', '')}";
        $data = Cache::remember($cacheKey, 3, function () use ($court, $request) {
            $court->load(['activeMatch', 'activeDrawing']);
            $match = $court->activeMatch
                ? MatchNumber::with(['athletes', 'embuScores'])->find($court->active_match_id)
                : null;

            return $this->getHasilState($match, $court->id, $court, $request);
        });

        $etag = md5(json_encode($data));

        return response()->json($data)
            ->header('Cache-Control', 'max-age=3, must-revalidate')
            ->header('ETag', $etag);
    }

    public function monitorHasilMatchState(Request $request, MatchNumber $match): JsonResponse
    {
        $cacheKey = "monitor_hasil_match_state_{$match->id}_{$request->query('round', '')}_{$request->query('pool_id', '')}";
        $data = Cache::remember($cacheKey, 3, function () use ($match, $request) {
            $match->load(['athletes', 'embuScores']);

            return $this->getHasilState($match, null, null, $request);
        });

        $etag = md5(json_encode($data));

        return response()->json($data)
            ->header('Cache-Control', 'max-age=3, must-revalidate')
            ->header('ETag', $etag);
    }

    private function getHasilState($match, $courtId, $court, Request $request): array
    {
        $drawingData = null;
        $randoriResults = collect();
        $embuRanking = collect();
        $activeNodeKey = null;

        if ($match) {
            if ($match->draft_type === 'randori') {
                $drawingData = $match->drawing_data;
                $randoriResults = RandoriMatchResult::where('match_number_id', $match->id)
                    ->get()
                    ->keyBy('bracket_node');
                $activeNodeKey = $court?->active_bracket_node ?? $match->active_bracket_node;
            } elseif ($match->draft_type === 'embu') {
                $embuRanking = $this->getPenyisihanRanking($match, $courtId, $request);
            }
        }

        return [
            'court' => $court,
            'match' => $match,
            'drawingData' => $drawingData,
            'randoriResults' => $randoriResults,
            'embuRanking' => $embuRanking,
            'activeNodeKey' => $activeNodeKey,
        ];
    }

    public function monitorRefereeState(Request $request, Court $court): JsonResponse
    {
        $rundownId = $request->query('rundown_id');
        $sessionId = $request->query('session_time_id');

        $cacheKey = "monitor_referee_state_{$court->id}_{$rundownId}_{$sessionId}";
        $data = Cache::remember($cacheKey, 3, function () use ($court, $rundownId, $sessionId) {
            if ($rundownId && $sessionId) {
                $referees = ScheduleReferee::with('referee.user')
                    ->where('court_id', $court->id)
                    ->where('rundown_id', $rundownId)
                    ->where('session_time_id', $sessionId)
                    ->where('judge_index', '>', 0)
                    ->orderBy('judge_index')
                    ->get();
            } else {
                $referees = ActiveCourtReferee::with('referee.user')
                    ->where('court_id', $court->id)
                    ->orderBy('judge_index')
                    ->get();

                if ($referees->isEmpty()) {
                    $activeDrawing = $court->activeDrawing;
                    if ($activeDrawing) {
                        $referees = ScheduleReferee::with('referee.user')
                            ->where('court_id', $court->id)
                            ->where('rundown_id', $activeDrawing->rundown_id)
                            ->where('session_time_id', $activeDrawing->session_time_id)
                            ->where('judge_index', '>', 0)
                            ->orderBy('judge_index')
                            ->get();
                    }
                }
            }

            return [
                'court' => $court,
                'referees' => $referees,
                'contextRundown' => $court->activeDrawing?->rundown,
                'contextSession' => $court->activeDrawing?->sessionTime,
            ];
        });

        $etag = md5(json_encode($data));

        return response()->json($data)
            ->header('Cache-Control', 'max-age=3, must-revalidate')
            ->header('ETag', $etag);
    }

    public function monitorRekapitulasiHasilState(Court $court): JsonResponse
    {
        $cacheKey = "monitor_rekap_hasil_state_{$court->id}";
        $data = Cache::remember($cacheKey, 3, function () use ($court) {
            $court->load(['activeMatch', 'activeDrawing']);
            $match = $court->activeMatch ? MatchNumber::find($court->active_match_id) : null;
            $scores = collect();
            $currentRound = 'Penyisihan';
            $poolName = null;

            if ($match) {
                $activeDrawing = $court->activeDrawing;
                $matchNumberIds = [$match->id];

                if ($match->mergeDetail) {
                    $matchNumberIds = MatchNumberMergeDetail::where('match_number_merge_id', $match->mergeDetail->match_number_merge_id)
                        ->pluck('match_number_id')
                        ->toArray();
                }

                $drawQuery = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)
                    ->where('draft_type', 'embu');

                $validActiveDrawing = $activeDrawing && in_array($activeDrawing->match_number_id, $matchNumberIds);

                if ($validActiveDrawing) {
                    if ($activeDrawing->pool_id) {
                        $drawQuery->where('pool_id', $activeDrawing->pool_id);
                        $poolName = $activeDrawing->pool?->name;
                    }
                    if ($activeDrawing->court_id) {
                        $drawQuery->where('court_id', $activeDrawing->court_id);
                    }
                    if ($activeDrawing->round) {
                        $drawQuery->where('round', $activeDrawing->round);
                    }
                    $currentRound = $activeDrawing->round ?? 'Penyisihan';
                } else {
                    $drawQuery->where('court_id', $court->id);
                    $firstDrawingOnCourt = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)
                        ->with('pool')
                        ->where('court_id', $court->id)
                        ->whereNotNull('pool_id')
                        ->first();
                    if ($firstDrawingOnCourt) {
                        $drawQuery->where('pool_id', $firstDrawingOnCourt->pool_id);
                        $currentRound = $firstDrawingOnCourt->round ?? 'Penyisihan';
                        $poolName = $firstDrawingOnCourt->pool?->name;
                    }
                }

                $drawings = $drawQuery->get();
                $drawingRegIds = $drawings->pluck('registration_id')->unique()->filter()->toArray();

                $registrations = Registration::with(['contingent', 'athletes'])->whereIn('id', $drawingRegIds)->get()->keyBy('id');
                $matchRecords = MatchNumber::whereIn('id', $matchNumberIds)->get()->keyBy('id');
                $allScores = EmbuScore::whereIn('match_number_id', $matchNumberIds)
                    ->where('round_label', $currentRound)
                    ->get();

                $scores = $drawings->map(function ($drawing) use ($registrations, $matchRecords, $allScores) {
                    $regId = $drawing->registration_id;
                    $reg = $registrations->get($regId);
                    $specificMatchId = $drawing->match_number_id;

                    $athleteIds = $drawing->metadata['athlete_ids'] ?? [];
                    $athletes = collect();
                    if (! empty($athleteIds)) {
                        $athletes = $reg?->athletes->whereIn('id', $athleteIds)->values() ?? collect();
                    } elseif ($reg) {
                        $athletes = $reg->athletes;
                    }

                    $matchRecord = $matchRecords->get($specificMatchId);

                    $score = $allScores->where('registration_id', $regId)
                        ->where('match_number_id', $specificMatchId)
                        ->where('drawing_id', $drawing->id)
                        ->sortByDesc('tiebreak_round')
                        ->first();

                    if (! $score) {
                        $score = $allScores->where('registration_id', $regId)
                            ->where('match_number_id', $specificMatchId)
                            ->whereNull('drawing_id')
                            ->sortByDesc('tiebreak_round')
                            ->first();
                    }

                    return (object) [
                        'registration_id' => $regId,
                        'drawing_id' => $drawing->id,
                        'registration' => $reg,
                        'athletes' => $athletes,
                        'match_number_id' => $specificMatchId,
                        'match_name' => $matchRecord?->name,
                        'score' => $score,
                        'judge_1' => $score?->judge_1 ?? 0,
                        'judge_2' => $score?->judge_2 ?? 0,
                        'judge_3' => $score?->judge_3 ?? 0,
                        'judge_4' => $score?->judge_4 ?? 0,
                        'judge_5' => $score?->judge_5 ?? 0,
                        'denda' => $score?->denda ?? 0,
                        'nilai_akhir' => $score?->effective_score ?? 0,
                        'effective_score' => $score?->effective_score ?? 0,
                    ];
                })
                    ->sortByDesc('effective_score')
                    ->values();
            }

            return [
                'court' => $court,
                'match' => $match,
                'scores' => $scores,
                'currentRound' => $currentRound,
                'poolName' => $poolName,
            ];
        });

        $etag = md5(json_encode($data));

        return response()->json($data)
            ->header('Cache-Control', 'max-age=3, must-revalidate')
            ->header('ETag', $etag);
    }

    public function monitorTimerState(Court $court): JsonResponse
    {
        $cacheKey = "monitor_timer_state_{$court->id}";
        $data = Cache::remember($cacheKey, 3, function () use ($court) {
            $court->load(['activeMatch.ageGroup', 'activeDrawing.registration.contingent']);
            $state = Cache::get("court_{$court->id}_timer", [
                'status' => 'stopped',
                'elapsed_ms' => 0,
                'started_at_ms' => null,
                'countdown_end_ms' => null,
            ]);
            $state['server_time_ms'] = floor(microtime(true) * 1000);

            return [
                'court' => $court,
                'timer_state' => $state,
            ];
        });

        $etag = md5(json_encode($data));

        return response()->json($data)
            ->header('Cache-Control', 'max-age=3, must-revalidate')
            ->header('ETag', $etag);
    }

    // --- Helper methods ---

    private function getPenyisihanRanking($match, $courtId, Request $request)
    {
        if (! $match || $match->draft_type !== 'embu') {
            return collect();
        }

        $activeDrawing = null;
        if ($courtId) {
            $court = Court::with('activeDrawing')->find($courtId);
            $activeDrawing = $court?->activeDrawing;
        }

        $matchIds = [$match->id];
        if ($match->mergeDetail) {
            $matchIds = MatchNumberMergeDetail::where('match_number_merge_id', $match->mergeDetail->match_number_merge_id)
                ->pluck('match_number_id')
                ->toArray();
        }

        $query = DrawingMatchNumber::whereIn('match_number_id', $matchIds)
            ->where('draft_type', 'embu');

        $currentRound = 'Penyisihan';

        if ($request->filled('round')) {
            $currentRound = $request->query('round');
            $query->where('round', $currentRound);

            if ($request->filled('pool_id')) {
                $query->where('pool_id', $request->query('pool_id'));
            } else {
                $firstDrawing = DrawingMatchNumber::whereIn('match_number_id', $matchIds)
                    ->where('round', $currentRound)
                    ->whereNotNull('pool_id')
                    ->first();
                if ($firstDrawing) {
                    $query->where('pool_id', $firstDrawing->pool_id);
                }
            }
        }

        $validActiveDrawing = $activeDrawing && in_array($activeDrawing->match_number_id, $matchIds);

        if ($validActiveDrawing) {
            if ($activeDrawing->pool_id) {
                $query->where('pool_id', $activeDrawing->pool_id);
            }
            if ($activeDrawing->court_id) {
                $query->where('court_id', $activeDrawing->court_id);
            }
            if ($activeDrawing->round) {
                $query->where('round', $activeDrawing->round);
            }
        } elseif ($courtId) {
            $query->where('court_id', $courtId);
            $firstDrawingOnCourt = DrawingMatchNumber::whereIn('match_number_id', $matchIds)
                ->where('court_id', $courtId)
                ->where('round', 'Penyisihan')
                ->whereNotNull('pool_id')
                ->first();
            if ($firstDrawingOnCourt) {
                $query->where('pool_id', $firstDrawingOnCourt->pool_id);
                $currentRound = $firstDrawingOnCourt->round ?? 'Penyisihan';
            }
        }

        if ($validActiveDrawing && $activeDrawing->round) {
            $currentRound = $activeDrawing->round;
        }

        $drawings = $query->get();
        $drawingRegIds = $drawings->pluck('registration_id')->unique()->filter()->toArray();

        $registrations = Registration::with(['contingent', 'athletes'])->whereIn('id', $drawingRegIds)->get()->keyBy('id');
        $allScores = EmbuScore::whereIn('match_number_id', $matchIds)
            ->where('round_label', $currentRound)
            ->get();

        $penyisihanScores = collect();
        if ($currentRound === 'Final') {
            $penyisihanScores = EmbuScore::whereIn('match_number_id', $matchIds)
                ->where('round_label', 'Penyisihan')
                ->get();
        }

        return $drawings->map(function ($drawing) use ($currentRound, $registrations, $allScores, $penyisihanScores) {
            $regId = $drawing->registration_id;
            $reg = $registrations->get($regId);
            $specificMatchId = $drawing->match_number_id;

            $athleteIds = $drawing->metadata['athlete_ids'] ?? [];
            $athletes = collect();
            if (! empty($athleteIds)) {
                $athletes = $reg?->athletes->whereIn('id', $athleteIds)->values() ?? collect();
            } elseif ($reg) {
                $athletes = $reg->athletes;
            }

            $score = $allScores->where('registration_id', $regId)
                ->where('match_number_id', $specificMatchId)
                ->where('drawing_id', $drawing->id)
                ->filter(fn ($s) => (int) $s->tiebreak_round === 0 || is_null($s->tiebreak_round))
                ->first();

            if (! $score) {
                $score = $allScores->where('registration_id', $regId)
                    ->where('match_number_id', $specificMatchId)
                    ->whereNull('drawing_id')
                    ->filter(fn ($s) => (int) $s->tiebreak_round === 0 || is_null($s->tiebreak_round))
                    ->first();
            }

            $tiebreakScore = $allScores->where('registration_id', $regId)
                ->where('match_number_id', $specificMatchId)
                ->where('drawing_id', $drawing->id)
                ->where('tiebreak_round', '>', 0)
                ->sortByDesc('tiebreak_round')
                ->first();

            if (! $tiebreakScore) {
                $tiebreakScore = $allScores->where('registration_id', $regId)
                    ->where('match_number_id', $specificMatchId)
                    ->whereNull('drawing_id')
                    ->where('tiebreak_round', '>', 0)
                    ->sortByDesc('tiebreak_round')
                    ->first();
            }

            $effectiveScore = $tiebreakScore ?? $score;
            $accumulatedScore = 0;

            $penyisihanScore = null;
            if ($currentRound === 'Final') {
                $pScore = $penyisihanScores->where('registration_id', $regId)
                    ->where('match_number_id', $specificMatchId)
                    ->where('drawing_id', $drawing->id)
                    ->filter(fn ($s) => (int) $s->tiebreak_round === 0 || is_null($s->tiebreak_round))
                    ->first();

                if (! $pScore) {
                    $pScore = $penyisihanScores->where('registration_id', $regId)
                        ->where('match_number_id', $specificMatchId)
                        ->whereNull('drawing_id')
                        ->filter(fn ($s) => (int) $s->tiebreak_round === 0 || is_null($s->tiebreak_round))
                        ->first();
                }

                $pTiebreak = $penyisihanScores->where('registration_id', $regId)
                    ->where('match_number_id', $specificMatchId)
                    ->where('drawing_id', $drawing->id)
                    ->where('tiebreak_round', '>', 0)
                    ->sortByDesc('tiebreak_round')
                    ->first();

                if (! $pTiebreak) {
                    $pTiebreak = $penyisihanScores->where('registration_id', $regId)
                        ->where('match_number_id', $specificMatchId)
                        ->whereNull('drawing_id')
                        ->where('tiebreak_round', '>', 0)
                        ->sortByDesc('tiebreak_round')
                        ->first();
                }

                $penyisihanScore = $pTiebreak ?? $pScore;

                if ($penyisihanScore) {
                    $accumulatedScore += $penyisihanScore->effective_score;
                }
            }

            if ($effectiveScore) {
                $accumulatedScore += $effectiveScore->effective_score;
            }

            $matchRecord = MatchNumber::find($specificMatchId);

            return [
                'id' => $regId,
                'drawing_id' => $drawing->id,
                'athletes' => $athletes,
                'contingent' => $reg?->contingent,
                'match_number_id' => $specificMatchId,
                'match_name' => $matchRecord?->name,
                'score' => $score,
                'tiebreak_score' => $tiebreakScore,
                'effective_score' => $effectiveScore,
                'penyisihan_score' => $penyisihanScore,
                'accumulated_score' => $accumulatedScore,
            ];
        })
            ->values();
    }
}
