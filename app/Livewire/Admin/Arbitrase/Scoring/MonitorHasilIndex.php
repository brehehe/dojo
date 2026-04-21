<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\Court\Court;
use App\Models\MatchNumber\MatchNumber;
use App\Models\DrawingMatchNumber;
use App\Models\Registration;
use App\Models\RandoriMatchResult;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class MonitorHasilIndex extends Component
{
    public ?int $courtId = null;
    public ?int $matchId = null;

    public function mount(?int $courtId = null, ?int $matchId = null)
    {
        $this->courtId = $courtId;
        $this->matchId = $matchId;
    }

    /**
     * Get sorting logic for Embu.
     */
    private function getPenyisihanRanking($match): Collection
    {
        if (! $match || $match->draft_type !== 'embu') {
            return collect();
        }

        return $match->athletes
            ->groupBy('pivot.registration_id')
            ->map(function ($athletes, $regId) use ($match) {
                $reg = Registration::with('contingent')->find($regId);

                $score = $match->embuScores
                    ->where('registration_id', $regId)
                    ->where('round_label', 'Penyisihan')
                    ->where('tiebreak_round', 0)
                    ->first();

                $tiebreakScore = $match->embuScores
                    ->where('registration_id', $regId)
                    ->where('round_label', 'Penyisihan')
                    ->where('tiebreak_round', '>', 0)
                    ->sortByDesc('tiebreak_round')
                    ->first();

                return [
                    'id' => $regId,
                    'athletes' => $athletes,
                    'contingent' => $reg?->contingent,
                    'score' => $score,
                    'tiebreak_score' => $tiebreakScore,
                    'effective_score' => $tiebreakScore ?? $score,
                ];
            })
            ->sortByDesc(fn ($r) => $r['effective_score']?->nilai_akhir ?? -1)
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

        if ($match) {
            if ($match->draft_type === 'randori') {
                $drawingData = $match->drawing_data;
                $randoriResults = RandoriMatchResult::where('match_number_id', $match->id)
                    ->get()
                    ->keyBy('bracket_node');
            } elseif ($match->draft_type === 'embu') {
                $embuRanking = $this->getPenyisihanRanking($match);
            }
        }

        return view('livewire.admin.arbitrase.scoring.monitor-hasil-index', [
            'court' => $court,
            'match' => $match,
            'drawingData' => $drawingData,
            'randoriResults' => $randoriResults,
            'embuRanking' => $embuRanking,
        ]);
    }
}
