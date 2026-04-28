<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use App\Models\MatchNumber\MatchNumber;
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

        $query = DrawingMatchNumber::where('match_number_id', $match->id)
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
                $firstDrawing = DrawingMatchNumber::where('match_number_id', $match->id)
                    ->where('round', $currentRound)
                    ->whereNotNull('pool_id')
                    ->first();
                if ($firstDrawing) {
                    $query->where('pool_id', $firstDrawing->pool_id);
                }
            }
        }

        // Active court overrides
        $validActiveDrawing = $activeDrawing && $activeDrawing->match_number_id === $match->id;

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
            // to avoid showing 10-15 people at once if they haven't clicked Panggil yet.
            $firstDrawingOnCourt = DrawingMatchNumber::where('match_number_id', $match->id)
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

        $drawingRegIds = $query->pluck('registration_id')->unique()->toArray();

        return $match->athletes
            ->filter(fn ($athlete) => in_array($athlete->pivot->registration_id, $drawingRegIds))
            ->groupBy('pivot.registration_id')
            ->map(function ($athletes, $regId) use ($match, $currentRound) {
                $reg = Registration::with('contingent')->find($regId);

                $score = $match->embuScores
                    ->where('registration_id', $regId)
                    ->where('round_label', $currentRound)
                    ->where('tiebreak_round', 0)
                    ->first();

                $tiebreakScore = $match->embuScores
                    ->where('registration_id', $regId)
                    ->where('round_label', $currentRound)
                    ->where('tiebreak_round', '>', 0)
                    ->sortByDesc('tiebreak_round')
                    ->first();

                $effectiveScore = $tiebreakScore ?? $score;
                $accumulatedScore = 0;

                // Accumulate Penyisihan score if we are in Final
                $penyisihanScore = null;
                if ($currentRound === 'Final') {
                    $pScore = $match->embuScores
                        ->where('registration_id', $regId)
                        ->where('round_label', 'Penyisihan')
                        ->where('tiebreak_round', 0)
                        ->first();

                    $pTiebreak = $match->embuScores
                        ->where('registration_id', $regId)
                        ->where('round_label', 'Penyisihan')
                        ->where('tiebreak_round', '>', 0)
                        ->sortByDesc('tiebreak_round')
                        ->first();

                    $penyisihanScore = $pTiebreak ?? $pScore;
                    if ($penyisihanScore) {
                        $accumulatedScore += $penyisihanScore->nilai_akhir;
                    }
                }

                if ($effectiveScore) {
                    $accumulatedScore += $effectiveScore->nilai_akhir;
                } elseif ($currentRound !== 'Final') {
                    // If not Final and no effective score, accumulated is 0
                    $accumulatedScore = 0;
                }

                return [
                    'id' => $regId,
                    'athletes' => $athletes,
                    'contingent' => $reg?->contingent,
                    'score' => $score,
                    'tiebreak_score' => $tiebreakScore,
                    'effective_score' => $effectiveScore,
                    'penyisihan_score' => $penyisihanScore,
                    'accumulated_score' => $accumulatedScore,
                ];
            })
            // In Shorinji Kempo, highest score is best for both rounds.
            ->sortBy(function ($r) use ($currentRound) {
                if ($currentRound === 'Penyisihan') {
                    // Higher is better. If 0 (hasn't played), put at bottom.
                    return $r['effective_score'] ? -$r['effective_score']->nilai_akhir : 1;
                } else {
                    // For Final, higher is better (accumulated).
                    // Even if they haven't played in Final yet, rank them by their Penyisihan score (which is now their accumulated score).
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
