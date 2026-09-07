<?php

namespace App\Services;

use App\Models\ActiveCourtReferee;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\MatchNumberMergeDetail;
use App\Models\RandoriMatchResult;
use App\Models\Registration;
use App\Models\ScheduleReferee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CourtMonitorService
{
    /**
     * Get court monitor state for polling.
     *
     * @return array<string, mixed>
     */
    public function getCourtState(Court $court): array
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

        return [
            'court' => $court,
            'timer_state' => $timerState,
        ];
    }

    /**
     * Get court timer state.
     *
     * @return array<string, mixed>
     */
    public function getTimerState(Court $court): array
    {
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
    }

    /**
     * Get referee state for court monitor.
     *
     * @return array<string, mixed>
     */
    public function getRefereeState(Request $request, Court $court): array
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

        return [
            'court' => $court,
            'referees' => $referees,
            'contextRundown' => $court->activeDrawing?->rundown,
            'contextSession' => $court->activeDrawing?->sessionTime,
        ];
    }

    /**
     * Get courts with active referees eager-loaded to prevent N+1 queries.
     */
    public function getCourtsWithReferees(?int $userCourtId = null): Collection
    {
        $courtQuery = Court::with([
            'activeMatch.category',
            'activeMatch.ageGroup',
            'activeRegistration.contingent',
            'activeDrawing.matchNumber.category',
            'activeDrawing.pool',
            'activeDrawing.sessionTime',
            'activeDrawing.rundown',
            'activeDrawing.registration.contingent',
            'activeCourtReferees.referee.user',
        ])->orderBy('order');

        if ($userCourtId) {
            $courtQuery->where('id', $userCourtId);
        }

        $courts = $courtQuery->get();

        // Assign current_referees directly from eager loaded relation
        foreach ($courts as $court) {
            $court->current_referees = $court->activeCourtReferees;
        }

        return $courts;
    }

    /**
     * Get match results state for monitor.
     *
     * @return array<string, mixed>
     */
    public function getHasilState(?MatchNumber $match, ?int $courtId, ?Court $court, Request $request): array
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

    /**
     * Calculate ranking for Embu Penyisihan/Final.
     */
    public function getPenyisihanRanking(?MatchNumber $match, ?int $courtId, Request $request): \Illuminate\Support\Collection
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
        $matchRecords = MatchNumber::whereIn('id', $matchIds)->get()->keyBy('id');
        $allScores = EmbuScore::whereIn('match_number_id', $matchIds)
            ->where('round_label', $currentRound)
            ->get();

        $penyisihanScores = collect();
        if ($currentRound === 'Final') {
            $penyisihanScores = EmbuScore::whereIn('match_number_id', $matchIds)
                ->where('round_label', 'Penyisihan')
                ->get();
        }

        return $drawings->map(function ($drawing) use ($currentRound, $registrations, $matchRecords, $allScores, $penyisihanScores) {
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

            // Use preloaded match records to avoid N+1 query inside loop
            $matchRecord = $matchRecords->get($specificMatchId);

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
        })->values();
    }
}
