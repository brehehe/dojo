<?php

namespace App\Http\Controllers;

use App\Models\ActiveCourtReferee;
use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\Group\AgeGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\MatchNumberMerge;
use App\Models\MatchNumberMergeDetail;
use App\Models\Pool\Pool;
use App\Models\RandoriMatchResult;
use App\Models\Referee;
use App\Models\Registration;
use App\Models\Rundown\Rundown;
use App\Models\SchedulePanitera;
use App\Models\ScheduleReferee;
use App\Models\SessionTime;
use App\Models\TournamentResult;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class SvelteMonitorController extends Controller
{
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

        return response()->json([
            'court' => $court,
            'timer_state' => $timerState,
        ]);
    }

    public function monitorHasilCourtState(Request $request, Court $court): JsonResponse
    {
        $court->load(['activeMatch', 'activeDrawing']);
        $match = $court->activeMatch ? MatchNumber::with(['athletes', 'embuScores'])->find($court->active_match_id) : null;

        return $this->getHasilState($match, $court->id, $court, $request);
    }

    public function monitorHasilMatchState(Request $request, MatchNumber $match): JsonResponse
    {
        $match->load(['athletes', 'embuScores']);

        return $this->getHasilState($match, null, null, $request);
    }

    private function getHasilState($match, $courtId, $court, Request $request): JsonResponse
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

        return response()->json([
            'court' => $court,
            'match' => $match,
            'drawingData' => $drawingData,
            'randoriResults' => $randoriResults,
            'embuRanking' => $embuRanking,
            'activeNodeKey' => $activeNodeKey,
        ]);
    }

    public function monitorRefereeState(Request $request, Court $court): JsonResponse
    {
        $rundownId = $request->query('rundown_id');
        $sessionId = $request->query('session_time_id');

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

        return response()->json([
            'court' => $court,
            'referees' => $referees,
            'contextRundown' => $court->activeDrawing?->rundown,
            'contextSession' => $court->activeDrawing?->sessionTime,
        ]);
    }

    public function monitorRekapitulasiHasilState(Court $court): JsonResponse
    {
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

        return response()->json([
            'court' => $court,
            'match' => $match,
            'scores' => $scores,
            'currentRound' => $currentRound,
            'poolName' => $poolName,
        ]);
    }

    public function monitorTimerState(Court $court): JsonResponse
    {
        $court->load(['activeMatch.ageGroup', 'activeDrawing.registration.contingent']);
        $state = Cache::get("court_{$court->id}_timer", [
            'status' => 'stopped',
            'elapsed_ms' => 0,
            'started_at_ms' => null,
            'countdown_end_ms' => null,
        ]);
        $state['server_time_ms'] = floor(microtime(true) * 1000);

        return response()->json([
            'court' => $court,
            'timer_state' => $state,
        ]);
    }

    // --- Helper methods copied from Livewire controllers ---

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

    public function panggilDrawingIndex(): Response
    {
        return Inertia::render('PanggilDrawingDashboard');
    }

    public function panggilDrawingState(Request $request): JsonResponse
    {
        $search = $request->input('search', '');
        $filterCourt = $request->input('filterCourt', '');
        $filterSession = $request->input('filterSession', '');
        $filterRundown = $request->input('filterRundown', '');
        $filterPool = $request->input('filterPool', '');
        $filterRound = $request->input('filterRound', '');
        $filterType = $request->input('filterType', '');
        $filterContingent = $request->input('filterContingent', '');
        $filterAgeGroup = $request->input('filterAgeGroup', '');
        $filterMatchNumber = $request->input('filterMatchNumber', '');
        $filterGender = $request->input('filterGender', '');

        $query = DrawingMatchNumber::with([
            'matchNumber.ageGroup',
            'pool',
            'court',
            'sessionTime',
            'rundown',
            'registration.contingent',
        ]);

        // Filters
        if (! empty($filterCourt)) {
            $query->where('court_id', $filterCourt);
        }
        if (! empty($filterSession)) {
            $query->where('session_time_id', $filterSession);
        }
        if (! empty($filterRundown)) {
            $query->where('rundown_id', $filterRundown);
        }
        if (! empty($filterPool)) {
            $query->where('pool_id', $filterPool);
        }
        if (! empty($filterRound)) {
            $query->where('round', $filterRound);
        }
        if (! empty($filterType)) {
            $query->where('draft_type', $filterType);
        }
        if (! empty($filterAgeGroup)) {
            $query->whereHas('matchNumber', function ($q) use ($filterAgeGroup) {
                $q->where('age_group_id', $filterAgeGroup);
            });
        }
        if (! empty($filterMatchNumber)) {
            $query->where('match_number_id', $filterMatchNumber);
        }
        if (! empty($filterGender)) {
            $query->whereHas('matchNumber', function ($q) use ($filterGender) {
                $q->where('gender', $filterGender);
            });
        }
        if (! empty($filterContingent)) {
            $query->whereHas('registration.contingent', function ($q) use ($filterContingent) {
                $q->where('id', $filterContingent);
            });
        }
        if (auth()->user()->court_id) {
            $query->where('drawing_match_numbers.court_id', auth()->user()->court_id);
        }
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('matchNumber', fn ($mq) => $mq->where('name', 'ilike', '%'.$search.'%'))
                    ->orWhereHas('registration.contingent', fn ($cq) => $cq->where('name', 'ilike', '%'.$search.'%'))
                    ->orWhereRaw("metadata->>'athlete_name' ilike ?", ['%'.$search.'%']);
            });
        }

        // Sorting: ASC date, ASC start_time, ASC sequence_number
        $query->join('rundowns', 'drawing_match_numbers.rundown_id', '=', 'rundowns.id')
            ->select('drawing_match_numbers.*')
            ->orderBy('rundowns.date', 'asc')
            ->orderBy(DB::raw("drawing_match_numbers.metadata->>'start_time'"), 'asc')
            ->orderBy('drawing_match_numbers.sequence_number', 'asc');

        $drawings = $query->paginate(15);

        // Map drawings to check if they have score/lock
        $drawings->getCollection()->transform(function ($drawing) {
            $hasScore = false;
            if ($drawing->draft_type === 'embu') {
                $hasScore = EmbuScore::where('drawing_id', $drawing->id)
                    ->orWhere(function ($q) use ($drawing) {
                        $q->where('registration_id', $drawing->registration_id)
                            ->where('match_number_id', $drawing->match_number_id);
                    })
                    ->exists();
            } else {
                // Randori score check
                $hasScore = RandoriMatchResult::where('match_number_id', $drawing->match_number_id)
                    ->exists();
            }
            $drawing->has_score = $hasScore;

            return $drawing;
        });

        // Other filters lists
        $courtQuery = Court::with([
            'activeMatch',
            'activeDrawing.pool',
            'activeDrawing.sessionTime',
            'activeDrawing.rundown',
            'activeDrawing.registration.contingent',
        ])->orderBy('order');

        if (auth()->user()->court_id) {
            $courtQuery->where('id', auth()->user()->court_id);
        }

        $courts = $courtQuery->get();

        foreach ($courts as $court) {
            $court->current_referees = ActiveCourtReferee::with('referee.user')
                ->where('court_id', $court->id)
                ->orderBy('judge_index')
                ->get();
        }
        $sessions = SessionTime::orderBy('start_time')->get();
        $rundowns = Rundown::orderBy('date')->get();
        $pools = Pool::orderBy('order')->get();
        $contingents = Contingent::orderBy('name')->get();
        $rounds = DrawingMatchNumber::whereNotNull('round')
            ->distinct()
            ->orderBy('round')
            ->pluck('round');
        $ageGroups = AgeGroup::orderBy('order')->get();

        $matchNumberQuery = MatchNumber::orderBy('name');
        if ($filterAgeGroup) {
            $matchNumberQuery->where('age_group_id', $filterAgeGroup);
        }
        if ($filterGender) {
            $matchNumberQuery->where('gender', $filterGender);
        }
        if ($filterType) {
            $matchNumberQuery->where('draft_type', $filterType);
        }
        $matchNumbers = $matchNumberQuery->get();

        $refereesQuery = Referee::with('user');
        $allReferees = $refereesQuery->get()->sortBy([
            ['certification_level', 'asc'],
        ])->values();

        return response()->json([
            'drawings' => $drawings,
            'courts' => $courts,
            'sessions' => $sessions,
            'rundowns' => $rundowns,
            'pools' => $pools,
            'contingents' => $contingents,
            'rounds' => $rounds,
            'ageGroups' => $ageGroups,
            'matchNumbers' => $matchNumbers,
            'allReferees' => $allReferees,
        ]);
    }

    // --- Svelte Admin Scoring Dashboard Page Renders and Actions ---

    public function scoringIndex(): Response
    {
        return Inertia::render('ScoringDashboard');
    }

    public function scoringEmbu(Request $request, MatchNumber $matchNumber): Response
    {
        return Inertia::render('ScoringEmbu', [
            'matchId' => $matchNumber->id,
            'urlRound' => $request->query('round'),
            'urlPoolId' => $request->query('pool_id') ? (int) $request->query('pool_id') : null,
            'urlFrom' => $request->query('from'),
        ]);
    }

    public function scoringRandori(Request $request, MatchNumber $matchNumber): Response
    {
        return Inertia::render('ScoringRandori', [
            'matchId' => $matchNumber->id,
            'urlRound' => $request->query('round'),
            'urlPoolId' => $request->query('pool_id') ? (int) $request->query('pool_id') : null,
            'urlFrom' => $request->query('from'),
        ]);
    }

    public function scoringDashboardState(Request $request): JsonResponse
    {
        $search = $request->input('search', '');
        $filterCourt = $request->input('filterCourt', '');
        $filterSession = $request->input('filterSession', '');
        $filterRundown = $request->input('filterRundown', '');
        $filterPool = $request->input('filterPool', '');
        $filterRound = $request->input('filterRound', '');
        $filterType = $request->input('filterType', '');
        $filterContingent = $request->input('filterContingent', '');
        $filterAgeGroup = $request->input('filterAgeGroup', '');
        $filterMatchNumber = $request->input('filterMatchNumber', '');
        $filterGender = $request->input('filterGender', '');
        $searchReferee = $request->input('searchReferee', '');

        $query = Court::with([
            'activeMatch',
            'activeDrawing.pool',
            'activeDrawing.sessionTime',
            'activeDrawing.rundown',
            'activeDrawing.registration.contingent',
        ])->orderBy('order');

        if (auth()->user()->court_id) {
            $query->where('id', auth()->user()->court_id);
        }

        $courts = $query->get();

        foreach ($courts as $court) {
            $court->current_referees = ActiveCourtReferee::with('referee.user')
                ->where('court_id', $court->id)
                ->orderBy('judge_index')
                ->get();
        }

        $sessions = SessionTime::orderBy('start_time')->get();
        $rundowns = Rundown::orderBy('date')->get();
        $pools = Pool::orderBy('order')->get();
        $contingents = Contingent::orderBy('name')->get();

        $rounds = DrawingMatchNumber::whereNotNull('round')
            ->distinct()
            ->orderBy('round')
            ->pluck('round');

        $ageGroups = AgeGroup::orderBy('order')->get();

        $matchNumberQuery = MatchNumber::orderBy('name');
        if ($filterAgeGroup) {
            $matchNumberQuery->where('age_group_id', $filterAgeGroup);
        }
        if ($filterGender) {
            $matchNumberQuery->where('gender', $filterGender);
        }
        if ($filterType) {
            $matchNumberQuery->where('draft_type', $filterType);
        }
        $matchNumbers = $matchNumberQuery->get();

        // Core query: grouped per scheduled match session
        $drawingsQuery = DrawingMatchNumber::query()
            ->join('match_numbers', 'drawing_match_numbers.match_number_id', '=', 'match_numbers.id')
            ->leftJoin('match_number_merge_details', 'match_numbers.id', '=', 'match_number_merge_details.match_number_id')
            ->leftJoin('match_number_merges', 'match_number_merge_details.match_number_merge_id', '=', 'match_number_merges.id')
            ->select(
                'drawing_match_numbers.court_id',
                'drawing_match_numbers.pool_id',
                'drawing_match_numbers.session_time_id',
                'drawing_match_numbers.rundown_id',
                'drawing_match_numbers.draft_type'
            )
            ->selectRaw("CASE WHEN drawing_match_numbers.draft_type = 'randori' THEN 'Full Bracket' ELSE drawing_match_numbers.round END as round")
            ->selectRaw('COALESCE(MAX(match_number_merges.name), \'\') as merge_name')
            ->selectRaw('STRING_AGG(DISTINCT match_numbers.name, \', \') as aggregated_match_names')
            ->selectRaw('MIN(drawing_match_numbers.match_number_id) as match_number_id')
            ->selectRaw('MIN(drawing_match_numbers.id) as id')
            ->selectRaw('COUNT(drawing_match_numbers.registration_id) as total_athletes')
            ->selectRaw('MIN(drawing_match_numbers.sequence_number) as sequence_number')
            ->groupBy(
                'drawing_match_numbers.court_id',
                'drawing_match_numbers.pool_id',
                'drawing_match_numbers.session_time_id',
                'drawing_match_numbers.rundown_id',
                DB::raw("CASE WHEN drawing_match_numbers.draft_type = 'randori' THEN 'Full Bracket' ELSE drawing_match_numbers.round END"),
                'drawing_match_numbers.draft_type',
                DB::raw('COALESCE(match_number_merges.id, -drawing_match_numbers.match_number_id)')
            )
            ->with([
                'matchNumber.ageGroup',
                'pool',
                'court',
                'sessionTime',
                'rundown',
            ]);

        // Filters
        if (! empty($filterCourt)) {
            $drawingsQuery->where('court_id', $filterCourt);
        }
        if (! empty($filterSession)) {
            $drawingsQuery->where('session_time_id', $filterSession);
        }
        if (! empty($filterRundown)) {
            $drawingsQuery->where('rundown_id', $filterRundown);
        }
        if (! empty($filterPool)) {
            $drawingsQuery->where('pool_id', $filterPool);
        }
        if (! empty($filterRound)) {
            $drawingsQuery->where('round', $filterRound);
        }
        if (! empty($filterType)) {
            $drawingsQuery->where('drawing_match_numbers.draft_type', $filterType);
        }
        if (! empty($filterAgeGroup)) {
            $drawingsQuery->whereHas('matchNumber', function ($q) use ($filterAgeGroup) {
                $q->where('age_group_id', $filterAgeGroup);
            });
        }
        if (! empty($filterMatchNumber)) {
            $drawingsQuery->where('match_number_id', $filterMatchNumber);
        }
        if (! empty($filterGender)) {
            $drawingsQuery->whereHas('matchNumber', function ($q) use ($filterGender) {
                $q->where('gender', $filterGender);
            });
        }

        if (auth()->user()->court_id) {
            $drawingsQuery->where('court_id', auth()->user()->court_id);
        }

        if (! empty($filterContingent)) {
            $drawingsQuery->whereHas('registration.contingent', function ($q) use ($filterContingent) {
                $q->where('contingents.id', $filterContingent);
            });
        }

        if (! empty($search)) {
            $drawingsQuery->where(function ($q) use ($search) {
                $q->whereHas('matchNumber', fn ($mq) => $mq->where('name', 'ilike', '%'.$search.'%'))
                    ->orWhereHas('registration.contingent', fn ($cq) => $cq->where('name', 'ilike', '%'.$search.'%'));
            });
        }

        $drawingsQuery->orderBy('rundown_id')->orderBy('session_time_id')->orderByRaw('MIN(sequence_number)');

        $drawings = $drawingsQuery->paginate(10);

        $refereesQuery = Referee::with('user');
        if (! empty($searchReferee)) {
            $refereesQuery->whereHas('user', function ($q) use ($searchReferee) {
                $q->where('name', 'ilike', '%'.$searchReferee.'%');
            })->orWhere('license_number', 'ilike', '%'.$searchReferee.'%')
                ->orWhere('certification_level', 'ilike', '%'.$searchReferee.'%');
        }
        $allReferees = $refereesQuery->get()->sortBy([
            ['certification_level', 'asc'],
        ])->values();

        return response()->json([
            'drawings' => $drawings,
            'courts' => $courts,
            'sessions' => $sessions,
            'rundowns' => $rundowns,
            'pools' => $pools,
            'contingents' => $contingents,
            'rounds' => $rounds,
            'ageGroups' => $ageGroups,
            'matchNumbers' => $matchNumbers,
            'allReferees' => $allReferees,
        ]);
    }

    public function activateMatch(Request $request): JsonResponse
    {
        $drawingId = $request->input('drawing_id');
        $drawing = DrawingMatchNumber::with([
            'matchNumber',
            'court',
            'registration.contingent',
            'pool',
            'sessionTime',
            'rundown',
        ])->findOrFail($drawingId);

        $court = $drawing->court;

        if (! $court) {
            return response()->json([
                'success' => false,
                'message' => 'Drawing ini belum memiliki lapangan yang ditentukan.',
            ], 400);
        }

        $matchNumber = $drawing->matchNumber;
        $draftType = $drawing->draft_type ?? $matchNumber?->draft_type ?? 'embu';

        if ($draftType === 'embu') {
            $matchNumber?->update(['active_registration_id' => $drawing->registration_id]);
        } else {
            $matchNumber?->update(['active_bracket_node' => '0_0']);
        }

        $court->update([
            'active_match_id' => $drawing->match_number_id,
            'active_drawing_id' => $drawing->id,
            'active_registration_id' => $drawing->registration_id,
            'active_bracket_node' => $draftType !== 'embu' ? '0_0' : null,
        ]);

        Cache::put("court_{$court->id}_timer", [
            'status' => 'stopped',
            'elapsed_ms' => 0,
            'started_at_ms' => null,
        ]);

        $contingentName = $drawing->registration?->contingent?->name ?? '—';
        $poolLabel = $drawing->pool ? 'Pool '.$drawing->pool->name : null;
        $sessionLabel = $drawing->sessionTime?->name;

        $text = implode(' · ', array_filter([
            $matchNumber?->name ?? '—',
            $contingentName,
            $poolLabel,
            $sessionLabel,
            '→ '.$court->name,
        ]));

        return response()->json([
            'success' => true,
            'title' => 'Pertandingan Aktif!',
            'text' => $text,
        ]);
    }

    public function clearCourt(Request $request): JsonResponse
    {
        $courtId = $request->input('court_id');
        $court = Court::findOrFail($courtId);
        $court->update([
            'active_match_id' => null,
            'active_registration_id' => null,
            'active_bracket_node' => null,
            'active_drawing_id' => null,
        ]);

        Cache::forget("court_{$courtId}_timer");

        return response()->json([
            'success' => true,
            'text' => $court->name.' sekarang idle / kosong.',
        ]);
    }

    public function clearAllCourts(): JsonResponse
    {
        $allCourts = Court::all();

        foreach ($allCourts as $court) {
            $court->update([
                'active_match_id' => null,
                'active_registration_id' => null,
                'active_bracket_node' => null,
                'active_drawing_id' => null,
            ]);

            Cache::forget("court_{$court->id}_timer");
        }

        MatchNumber::query()->update([
            'active_registration_id' => null,
            'active_bracket_node' => null,
        ]);

        return response()->json([
            'success' => true,
            'text' => 'Seluruh status aktif telah dibersihkan secara serentak.',
        ]);
    }

    public function saveRefereeAssignment(Request $request): JsonResponse
    {
        $request->validate([
            'court_id' => 'required',
            'rundown_id' => 'required',
            'session_time_id' => 'required',
            'referees' => 'required|array|min:5|max:5',
        ], [
            'referees.min' => 'Wajib memilih tepat 5 wasit.',
            'referees.max' => 'Wajib memilih tepat 5 wasit.',
        ]);

        $courtId = $request->input('court_id');
        $rundownId = $request->input('rundown_id');
        $sessionId = $request->input('session_time_id');
        $selectedReferees = $request->input('referees');

        DB::beginTransaction();
        try {
            ScheduleReferee::where('rundown_id', $rundownId)
                ->where('session_time_id', $sessionId)
                ->where('court_id', $courtId)
                ->where('judge_index', '>', 0)
                ->delete();

            foreach ($selectedReferees as $index => $refereeId) {
                ScheduleReferee::create([
                    'rundown_id' => $rundownId,
                    'session_time_id' => $sessionId,
                    'court_id' => $courtId,
                    'referee_id' => $refereeId,
                    'judge_index' => $index + 1,
                ]);
            }

            ActiveCourtReferee::where('court_id', $courtId)->delete();
            foreach ($selectedReferees as $index => $refereeId) {
                ActiveCourtReferee::create([
                    'court_id' => $courtId,
                    'referee_id' => $refereeId,
                    'judge_index' => $index + 1,
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'text' => 'Panel wasit telah diperbarui.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function resetActiveReferees(Request $request): JsonResponse
    {
        $courtId = $request->input('court_id');
        ActiveCourtReferee::where('court_id', $courtId)->delete();

        return response()->json([
            'success' => true,
            'text' => 'Seluruh wasit aktif untuk lapangan ini telah dihapus.',
        ]);
    }

    public function resetCourtReferees(Request $request): JsonResponse
    {
        $courtId = $request->input('court_id');
        $rundownId = $request->input('rundown_id');
        $sessionId = $request->input('session_time_id');

        if (! $courtId || ! $rundownId || ! $sessionId) {
            return response()->json(['success' => false, 'message' => 'Parameter tidak lengkap.'], 400);
        }

        ScheduleReferee::where('court_id', $courtId)
            ->where('rundown_id', $rundownId)
            ->where('session_time_id', $sessionId)
            ->where('judge_index', '>', 0)
            ->delete();

        ActiveCourtReferee::where('court_id', $courtId)->delete();

        return response()->json([
            'success' => true,
            'text' => 'Seluruh wasit untuk sesi ini telah dikosongkan.',
        ]);
    }

    public function timerControl(Request $request): JsonResponse
    {
        $courtId = $request->input('court_id');
        $action = $request->input('action');

        if (! $courtId) {
            return response()->json(['success' => false, 'message' => 'Court ID required.'], 400);
        }

        $state = Cache::get("court_{$courtId}_timer", ['status' => 'stopped', 'elapsed_ms' => 0, 'started_at_ms' => null]);

        if ($action === 'countdown') {
            $state['status'] = 'countdown';
            $state['countdown_end_ms'] = floor(microtime(true) * 1000) + 5000;
        } elseif ($action === 'start') {
            if ($state['status'] !== 'running') {
                $state['status'] = 'running';
                $state['started_at_ms'] = floor(microtime(true) * 1000);
            }
        } elseif ($action === 'pause') {
            if ($state['status'] === 'running') {
                $now = floor(microtime(true) * 1000);
                $elapsedSinceStart = $now - $state['started_at_ms'];
                $state['status'] = 'paused';
                $state['elapsed_ms'] += $elapsedSinceStart;
                $state['started_at_ms'] = null;
            }
        } elseif ($action === 'stop') {
            $state = [
                'status' => 'stopped',
                'elapsed_ms' => 0,
                'started_at_ms' => null,
            ];
        }

        Cache::put("court_{$courtId}_timer", $state);
        $state['server_time_ms'] = floor(microtime(true) * 1000);

        return response()->json([
            'success' => true,
            'timer_state' => $state,
        ]);
    }

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
        $drawingsQuery = DrawingMatchNumber::with(['registration.contingent'])
            ->whereIn('match_number_id', $matchNumberIds)
            ->where('round', $currentRound);

        if ($currentRound === 'Penyisihan' && $selectedPoolId) {
            $drawingsQuery->where('pool_id', $selectedPoolId);
        }

        $drawingsList = $drawingsQuery->orderBy('sequence_number')->get();

        $allScores = EmbuScore::whereIn('match_number_id', $matchNumberIds)
            ->where('round_label', $currentRound)
            ->get();

        $registrations = $drawingsList->map(function ($drawing) use ($allScores, $matchNumberIds, $currentRound) {
            $regId = $drawing->registration_id;
            $matchId = $drawing->match_number_id;
            $registration = $drawing->registration;

            $metaAthleteIds = $drawing->metadata['athlete_ids'] ?? [];

            if (! empty($metaAthleteIds)) {
                $athletes = Athlete::whereIn('id', $metaAthleteIds)->get();
            } else {
                $athletes = Athlete::whereHas('matchNumbers', function ($q) use ($matchId, $regId) {
                    $q->where('match_numbers.id', $matchId)
                        ->where('athlete_match_number.registration_id', $regId);
                })->get();
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
                $penyisihanScore = EmbuScore::whereIn('match_number_id', $matchNumberIds)
                    ->where('registration_id', $regId)
                    ->where('round_label', 'Penyisihan')
                    ->orderByDesc('tiebreak_round')
                    ->first();

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

        $tiedIds = $this->detectTiesForState($matchNumberIds, $currentRound);

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

        return response()->json([
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
        ]);
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
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Drawing tidak ditemukan.'], 404);
    }

    public function embuCallParticipant(Request $request): JsonResponse
    {
        $drawingId = $request->input('drawing_id');
        $drawing = DrawingMatchNumber::with(['court', 'pool', 'registration.contingent', 'registration.athletes', 'matchNumber'])
            ->findOrFail($drawingId);

        if (! $drawing->registration) {
            return response()->json([
                'success' => false,
                'message' => 'Peserta lolos babak final belum digenerate.',
            ], 400);
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

            return response()->json([
                'success' => true,
                'announcement_text' => $announcementText,
                'text' => 'Layar wasit dan TV Monitor kini terpusat ke peserta ini.',
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Lapangan tidak ditemukan pada drawing.'], 400);
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

            Court::where('id', $courtId)->update([
                'active_registration_id' => null,
                'active_drawing_id' => null,
                'active_match_id' => null,
                'active_bracket_node' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'text' => 'Wasit, TV Monitor, dan Timer telah direset.',
        ]);
    }

    public function embuFinishMatch(Request $request): JsonResponse
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

        return response()->json([
            'success' => true,
            'denda' => $denda,
            'seconds' => $seconds,
            'text' => 'Waktu dan denda telah diakumulasi, Panggilan ditutup.',
        ]);
    }

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
            $drawingData = $this->migrateLegacyBracketForController($drawingData);
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

        return response()->json([
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
        ]);
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
            return response()->json(['success' => false, 'message' => 'UB rounds empty.'], 400);
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
                $data = $this->placeAthleteForController($data, $match['winner_next'], $winnerData, $matchNumberIds);
            }

            if ($loserData && ($match['loser_next'] ?? null)) {
                $lb = $match['loser_next']['bracket'] ?? 'eliminated';
                if ($lb === 'lb') {
                    $data = $this->placeAthleteForController($data, $match['loser_next'], $loserData, $matchNumberIds);
                } elseif ($lb === 'ranked') {
                    $data['juara'][$match['loser_next']['rank']] = $loserData;
                }
            }

            if ($bracket === 'gf') {
                $data['juara'][1] = $winnerData;
                $data['juara'][2] = $loserData;
            }
        }

        $data = $this->propagateBracketByesForController($data, $matchNumberIds);

        $matchNumber->update(['drawing_data' => $data]);

        return response()->json([
            'success' => true,
            'text' => 'Routing diperbaiki & '.count($results).' hasil di-replay ulang.',
        ]);
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
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Drawing tidak ditemukan.'], 404);
    }

    public function randoriCallMatch(Request $request): JsonResponse
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
                ]);
            }
        }

        return response()->json(['success' => true, 'text' => 'Pertandingan dipanggil.']);
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
                ]);
            }
        }

        return response()->json(['success' => true, 'text' => 'Grand Final dipanggil.']);
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
        }

        MatchNumber::whereIn('id', $matchNumberIds)->update(['active_bracket_node' => null]);

        return response()->json([
            'success' => true,
            'text' => 'Panggilan ditutup, layar wasit dan TV Monitor telah direset.',
        ]);
    }

    public function randoriSubmitScoring(Request $request): JsonResponse
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
            return response()->json(['success' => false, 'message' => 'Nama dan Tanda tangan Arbitrase wajib diisi.'], 400);
        }
        if (empty($signatures['koordinator']['name']) || empty($signatures['koordinator']['signature'])) {
            return response()->json(['success' => false, 'message' => 'Nama dan Tanda tangan Koordinator wajib diisi.'], 400);
        }
        if (empty($signatures['wasit']['name']) || empty($signatures['wasit']['signature'])) {
            return response()->json(['success' => false, 'message' => 'Nama dan Tanda tangan Wasit wajib diisi.'], 400);
        }
        foreach ($signatures['panitera'] as $idx => $p) {
            if (empty($p['name']) || empty($p['signature'])) {
                return response()->json(['success' => false, 'message' => 'Nama dan Tanda tangan Panitera ke-'.($idx + 1).' wajib diisi.'], 400);
            }
        }
        if (empty($signatures['manager_red']['name']) || empty($signatures['manager_red']['signature'])) {
            return response()->json(['success' => false, 'message' => 'Nama dan Tanda tangan Manajer Pita Merah wajib diisi.'], 400);
        }
        if (empty($signatures['manager_white']['name']) || empty($signatures['manager_white']['signature'])) {
            return response()->json(['success' => false, 'message' => 'Nama dan Tanda tangan Manajer Pita Putih wajib diisi.'], 400);
        }

        if ($scoreRed === $scoreBlue) {
            return response()->json(['success' => false, 'message' => 'Poin sama (Hikiwake). Silakan tentukan pemenang lewat yusei_kachi atau mujoken_kachi.'], 400);
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
            return response()->json(['success' => false, 'message' => 'Bracket tidak valid.'], 400);
        }

        if (! $match) {
            return response()->json(['success' => false, 'message' => 'Match tidak ditemukan.'], 404);
        }

        $winnerData = $match[$winnerSlot] ?? null;
        $loserSlot = $winnerSlot === 'athlete1' ? 'athlete2' : 'athlete1';
        $loserData = $match[$loserSlot] ?? null;

        if (! $winnerData) {
            return response()->json(['success' => false, 'message' => 'Winner data empty.'], 400);
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
                $data = $this->placeAthleteForController($data, $match['winner_next'], $winnerData, $matchNumberIds);
            }
        }

        if ($loserData && ($match['loser_next'] ?? null)) {
            if ($match['loser_next']['bracket'] === 'lb') {
                $data = $this->placeAthleteForController($data, $match['loser_next'], $loserData, $matchNumberIds);
            } elseif ($match['loser_next']['bracket'] === 'ranked') {
                $data['juara'][$match['loser_next']['rank']] = $loserData;
            }
        }

        if ($bracket === 'gf') {
            $data['juara'][1] = $winnerData;
            $data['juara'][2] = $loserData;
        }

        $data = $this->propagateBracketByesForController($data, $matchNumberIds);

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
        }

        return response()->json([
            'success' => true,
            'text' => 'Pemenang dicatat & bracket terupdate.',
        ]);
    }

    public function randoriConfirmChampion(Request $request): JsonResponse
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
            return response()->json(['success' => false, 'message' => 'Tentukan pemenang Grand Final terlebih dahulu.'], 400);
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

        return response()->json([
            'success' => true,
            'text' => 'Daftar juara telah berhasil dicatat ke sistem.',
        ]);
    }

    public function embuSaveScore(Request $request): JsonResponse
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

        $this->recalculateRanksForController($matchNumberIds, $round);

        return response()->json([
            'success' => true,
            'text' => 'Nilai Berhasil Disimpan. Total: '.number_format($total, 1).' | Nilai Akhir: '.number_format($nilaiAkhir, 1),
        ]);
    }

    public function embuRequestTiebreak(Request $request): JsonResponse
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

        return response()->json([
            'success' => true,
            'text' => count($registrationIds).' peserta akan mengulangi penilaian.',
        ]);
    }

    public function embuAdvanceToFinal(Request $request): JsonResponse
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
            return response()->json(['success' => false, 'message' => 'Belum ada nilai penyisihan'], 400);
        }

        if (! empty($ties)) {
            return response()->json([
                'success' => false,
                'message' => 'Terdapat '.count($ties).' peserta dengan nilai yang sama. Lakukan Tanding Ulang terlebih dahulu.',
            ], 400);
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

        return response()->json([
            'success' => true,
            'text' => $qualifiers->count().' peserta berhasil lolos ke babak Final.',
        ]);
    }

    private function recalculateRanksForController(array $matchNumberIds, string $currentRound): void
    {
        $scores = EmbuScore::whereIn('match_number_id', $matchNumberIds)
            ->where('round_label', $currentRound)
            ->get();

        if ($currentRound === 'Penyisihan') {
            $sorted = $scores->sort(function ($a, $b) {
                if ($a->nilai_akhir != $b->nilai_akhir) {
                    return $b->nilai_akhir <=> $a->nilai_akhir;
                }

                return $b->judge_1 <=> $a->judge_1;
            })->values();
        } else {
            $penyisihanScores = EmbuScore::whereIn('match_number_id', $matchNumberIds)
                ->where('round_label', 'Penyisihan')
                ->get()
                ->groupBy('registration_id')
                ->map(fn ($group) => $group->sortByDesc('tiebreak_round')->first());

            $sorted = $scores->sort(function ($a, $b) use ($penyisihanScores) {
                $pA = $penyisihanScores[$a->registration_id] ?? null;
                $pB = $penyisihanScores[$b->registration_id] ?? null;

                $totalA = $a->nilai_akhir + ($pA ? $pA->nilai_akhir : 0);
                $totalB = $b->nilai_akhir + ($pB ? $pB->nilai_akhir : 0);

                if ($totalA != $totalB) {
                    return $totalB <=> $totalA;
                }

                return $b->judge_1 <=> $a->judge_1;
            })->values();
        }

        foreach ($sorted as $index => $score) {
            $score->update(['rank' => $index + 1]);
        }
    }

    private function detectTiesForState(array $matchNumberIds, string $currentRound): array
    {
        if ($currentRound !== 'Penyisihan') {
            return [];
        }

        $scores = EmbuScore::whereIn('match_number_id', $matchNumberIds)
            ->where('round_label', 'Penyisihan')
            ->get();

        $sorted = $scores->sort(function ($a, $b) {
            if ($a->nilai_akhir != $b->nilai_akhir) {
                return $b->nilai_akhir <=> $a->nilai_akhir;
            }

            return $b->judge_1 <=> $a->judge_1;
        })->values();

        $firstMatch = MatchNumber::find($matchNumberIds[0] ?? null);
        $drawing = $firstMatch ? $firstMatch->drawing_data : null;
        $threshold = 4;
        if ($drawing && isset($drawing['qualifiers'])) {
            $threshold = (int) $drawing['qualifiers'];
        }

        if ($sorted->count() <= $threshold) {
            return [];
        }

        $boundaryScore = $sorted->get($threshold - 1);
        if (! $boundaryScore) {
            return [];
        }

        $tied = $sorted->filter(
            fn ($s) => (float) $s->nilai_akhir === (float) $boundaryScore->nilai_akhir &&
            (float) $s->judge_1 === (float) $boundaryScore->judge_1
        );

        return $tied->count() > 1 ? $tied->pluck('registration_id')->toArray() : [];
    }

    private function migrateLegacyBracketForController(array $data): array
    {
        if (empty($data) || (! isset($data['rounds']) && ! isset($data['bracket']))) {
            return $data;
        }

        $rounds = $data['rounds'] ?? [];
        $bracketSize = $data['bracket_size'] ?? 0;

        if (empty($rounds) || $bracketSize < 2) {
            return $data;
        }

        $ubRounds = [];
        foreach ($rounds as $r => $matches) {
            $roundArr = [];
            foreach ($matches as $m => $match) {
                $roundArr[] = array_merge($match, [
                    'winner' => null,
                    'winner_data' => null,
                    'winner_next' => null,
                    'loser_next' => null,
                ]);
            }
            $ubRounds[] = $roundArr;
        }

        return [
            'bracket_type' => 'double_elimination',
            'bracket_size' => $bracketSize,
            'total_athletes' => $data['total_entries'] ?? 0,
            'upper_bracket' => ['rounds' => $ubRounds],
            'lower_bracket' => ['rounds' => []],
            'grand_final' => ['athlete1' => null, 'athlete2' => null, 'winner' => null, 'winner_data' => null],
            'juara' => [],
        ];
    }

    private function placeAthleteForController(array $data, array $next, array $athleteData, array $matchNumberIds): array
    {
        $b = $next['bracket'];
        $slot = $next['slot'] ?? 'athlete1';
        if ($b === 'ub') {
            $data['upper_bracket']['rounds'][$next['round']][$next['match']][$slot] = $athleteData;
        } elseif ($b === 'lb') {
            $data['lower_bracket']['rounds'][$next['round']][$next['match']][$slot] = $athleteData;
        } elseif ($b === 'gf') {
            $data['grand_final'][$slot] = $athleteData;
        }

        $round = $next['round'] ?? 0;
        $matchVal = $next['match'] ?? 0;
        $nodeKey = $b.'_'.$round.'_'.$matchVal;
        $side = $slot === 'athlete1' ? 'RED' : 'BLUE';

        $drawings = DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)->get();

        foreach ($drawings as $d) {
            $meta = $d->metadata;
            if (is_string($meta)) {
                $meta = json_decode($meta, true);
            }

            if (($meta['node_key'] ?? null) === $nodeKey && ($meta['side'] ?? null) === $side) {
                $d->registration_id = $athleteData['registration_id'] ?? null;
                $meta['athlete_id'] = $athleteData['id'] ?? null;
                $meta['athlete_name'] = $athleteData['name'] ?? 'TBD';
                $meta['contingent'] = $athleteData['contingent'] ?? 'TBD';
                $d->metadata = $meta;
                $d->save();
            }
        }

        return $data;
    }

    private function propagateBracketByesForController(array $data, array $matchNumberIds): array
    {
        $changed = true;
        $maxIterations = 10;
        $iteration = 0;

        while ($changed && $iteration < $maxIterations) {
            $changed = false;
            $iteration++;

            if (isset($data['upper_bracket']['rounds'])) {
                foreach ($data['upper_bracket']['rounds'] as $rIdx => $round) {
                    foreach ($round as $mIdx => $match) {
                        if (($match['winner'] ?? null) === null) {
                            $a1Bye = ($match['athlete1']['id'] ?? '') === 'BYE';
                            $a2Bye = ($match['athlete2']['id'] ?? '') === 'BYE';

                            if ($a1Bye || $a2Bye) {
                                $winnerSlot = $a1Bye ? 'athlete2' : 'athlete1';
                                $data['upper_bracket']['rounds'][$rIdx][$mIdx]['winner'] = $winnerSlot;
                                $data['upper_bracket']['rounds'][$rIdx][$mIdx]['winner_data'] = $match[$winnerSlot] ?? null;
                                $data['upper_bracket']['rounds'][$rIdx][$mIdx]['is_bye'] = true;
                                $changed = true;
                                $match = $data['upper_bracket']['rounds'][$rIdx][$mIdx];
                            }
                        }

                        if (($match['winner'] ?? null) !== null && ($match['winner_next'] ?? null)) {
                            $winnerData = $match['winner_data'] ?? $match[$match['winner']] ?? null;
                            if ($winnerData) {
                                $next = $match['winner_next'];
                                $target = $this->getAthleteInSlot($data, $next);
                                if (! $target || $target['id'] !== $winnerData['id']) {
                                    $data = $this->placeAthleteForController($data, $next, $winnerData, $matchNumberIds);
                                    $changed = true;
                                    $match = $data['upper_bracket']['rounds'][$rIdx][$mIdx];
                                }
                            }
                        }
                    }
                }
            }

            if (isset($data['lower_bracket']['rounds'])) {
                foreach ($data['lower_bracket']['rounds'] as $rIdx => $round) {
                    foreach ($round as $mIdx => $match) {
                        if (($match['winner'] ?? null) === null) {
                            $a1Bye = ($match['athlete1']['id'] ?? '') === 'BYE';
                            $a2Bye = ($match['athlete2']['id'] ?? '') === 'BYE';

                            if ($a1Bye || $a2Bye) {
                                $winnerSlot = $a1Bye ? 'athlete2' : 'athlete1';
                                $data['lower_bracket']['rounds'][$rIdx][$mIdx]['winner'] = $winnerSlot;
                                $data['lower_bracket']['rounds'][$rIdx][$mIdx]['winner_data'] = $match[$winnerSlot] ?? null;
                                $data['lower_bracket']['rounds'][$rIdx][$mIdx]['is_bye'] = true;
                                $changed = true;
                                $match = $data['lower_bracket']['rounds'][$rIdx][$mIdx];
                            }
                        }

                        if (($match['winner'] ?? null) !== null && ($match['winner_next'] ?? null)) {
                            $winnerData = $match['winner_data'] ?? $match[$match['winner']] ?? null;
                            if ($winnerData) {
                                $next = $match['winner_next'];
                                $target = $this->getAthleteInSlot($data, $next);
                                if (! $target || $target['id'] !== $winnerData['id']) {
                                    $data = $this->placeAthleteForController($data, $next, $winnerData, $matchNumberIds);
                                    $changed = true;
                                    $match = $data['lower_bracket']['rounds'][$rIdx][$mIdx];
                                }
                            }
                        }
                    }
                }
            }
        }

        return $data;
    }

    private function getAthleteInSlot(array $data, array $next): ?array
    {
        $b = $next['bracket'];
        $slot = $next['slot'] ?? 'athlete1';
        if ($b === 'ub') {
            return $data['upper_bracket']['rounds'][$next['round']][$next['match']][$slot] ?? null;
        }
        if ($b === 'lb') {
            return $data['lower_bracket']['rounds'][$next['round']][$next['match']][$slot] ?? null;
        }
        if ($b === 'gf') {
            return $data['grand_final'][$slot] ?? null;
        }

        return null;
    }
}
