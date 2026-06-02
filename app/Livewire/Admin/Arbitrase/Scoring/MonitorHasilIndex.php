<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\MatchNumberMergeDetail;
use App\Models\RandoriMatchResult;
use App\Models\Registration;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.guest')]
class MonitorHasilIndex extends Component
{
    public ?int $courtId = null;

    public ?int $matchId = null;

    #[Url(as: 'pool_id')]
    public ?int $poolId = null;

    #[Url(as: 'round')]
    public ?string $round = null;

    public function mount(?int $courtId = null, ?int $matchId = null)
    {
        $this->courtId = $courtId;
        $this->matchId = $matchId;
    }

    /**
     * Get sorting logic for Embu.
     */
    private function getPenyisihanRanking($match, $courtId = null): Collection
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

        // Apply URL filters if viewing by Match
        $currentRound = 'Penyisihan';

        if ($this->matchId) {
            // Default to 'Penyisihan' if no round provided
            $currentRound = $this->round ?? 'Penyisihan';
            $query->where('round', $currentRound);

            if ($this->poolId) {
                $query->where('pool_id', $this->poolId);
            } else {
                // If no pool_id specified, pick the first available pool for this round
                $firstDrawing = DrawingMatchNumber::whereIn('match_number_id', $matchIds)
                    ->where('round', $currentRound)
                    ->whereNotNull('pool_id')
                    ->first();
                if ($firstDrawing) {
                    $query->where('pool_id', $firstDrawing->pool_id);
                }
            }
        }

        // Active court overrides
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
            // If multiple pools are on this court for this match, just pick the first one by default
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

        // Eager load registrations to avoid N+1
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

            // Correctly filter athletes for this specific team/drawing
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

            // Accumulate Penyisihan score if we are in Final
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
            ->sortBy(function ($r) use ($currentRound) {
                if ($currentRound === 'Penyisihan') {
                    return $r['effective_score'] ? -$r['effective_score']->effective_score : 1;
                } else {
                    return -$r['accumulated_score'];
                }
            })
            ->values();
    }

    public function render()
    {
        $court = null;
        $match = null;
        $drawingData = null;
        $randoriResults = collect();
        $embuRanking = collect();

        if ($this->matchId) {
            $match = MatchNumber::with(['athletes', 'embuScores'])->find($this->matchId);
        } elseif ($this->courtId) {
            $court = Court::with(['activeMatch', 'activeDrawing'])->find($this->courtId);
            if ($court && $court->active_match_id) {
                $match = MatchNumber::with(['athletes', 'embuScores'])->find($court->active_match_id);
            }
        }

        $activeNodeKey = null;

        if ($match) {
            if ($match->draft_type === 'randori') {
                $drawingData = $match->drawing_data;
                $randoriResults = RandoriMatchResult::where('match_number_id', $match->id)
                    ->get()
                    ->keyBy('bracket_node');
                $activeNodeKey = $court?->active_bracket_node ?? $match->active_bracket_node;
            } elseif ($match->draft_type === 'embu') {
                $embuRanking = $this->getPenyisihanRanking($match, $this->courtId);
            }
        }

        return view('livewire.admin.arbitrase.scoring.monitor-hasil-index', [
            'court' => $court,
            'match' => $match,
            'drawingData' => $drawingData,
            'randoriResults' => $randoriResults,
            'embuRanking' => $embuRanking,
            'activeNodeKey' => $activeNodeKey,
        ]);
    }
}
