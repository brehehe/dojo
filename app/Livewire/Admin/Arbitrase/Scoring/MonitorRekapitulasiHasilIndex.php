<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\Court\Court;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\EmbuScore;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class MonitorRekapitulasiHasilIndex extends Component
{
    public ?int $courtId = null;
    public ?int $matchId = null;

    public function mount(?int $courtId = null, ?int $matchId = null)
    {
        $this->courtId = $courtId;
        $this->matchId = $matchId;
    }

    public function render()
    {
        $court = null;
        $match = null;
        $scores = collect();

        if ($this->matchId) {
            $match = MatchNumber::find($this->matchId);
        } elseif ($this->courtId) {
            $court = Court::with(['activeMatch', 'activeDrawing'])->find($this->courtId);
            if ($court && $court->active_match_id) {
                $match = MatchNumber::find($court->active_match_id);
            }
        }

        $poolName = null;
        if ($match) {
            $activeDrawing = $court?->activeDrawing;
            
            $drawQuery = \App\Models\DrawingMatchNumber::where('match_number_id', $match->id)
                ->where('draft_type', 'embu');

            $currentRound = 'Penyisihan';
            $validActiveDrawing = $activeDrawing && $activeDrawing->match_number_id === $match->id;

            if ($validActiveDrawing) {
                if ($activeDrawing->pool_id) {
                    $drawQuery->where('pool_id', $activeDrawing->pool_id);
                    $poolName = $activeDrawing->pool?->name;
                }
                if ($activeDrawing->court_id) $drawQuery->where('court_id', $activeDrawing->court_id);
                if ($activeDrawing->round) $drawQuery->where('round', $activeDrawing->round);
                $currentRound = $activeDrawing->round ?? 'Penyisihan';
            } elseif ($this->courtId) {
                $drawQuery->where('court_id', $this->courtId);
                $firstDrawingOnCourt = \App\Models\DrawingMatchNumber::where('match_number_id', $match->id)
                    ->with('pool')
                    ->where('court_id', $this->courtId)
                    ->whereNotNull('pool_id')
                    ->first();
                if ($firstDrawingOnCourt) {
                    $drawQuery->where('pool_id', $firstDrawingOnCourt->pool_id);
                    $currentRound = $firstDrawingOnCourt->round ?? 'Penyisihan';
                    $poolName = $firstDrawingOnCourt->pool?->name;
                }
            }

            $drawingRegIds = $drawQuery->pluck('registration_id')->unique()->toArray();

            $scores = $match->athletes
                ->filter(fn($athlete) => in_array($athlete->pivot->registration_id, $drawingRegIds))
                ->groupBy('pivot.registration_id')
                ->map(function($athletes, $regId) use ($match, $currentRound) {
                    $reg = Registration::with('contingent')->find($regId);
                    $score = EmbuScore::where('match_number_id', $match->id)
                        ->where('registration_id', $regId)
                        ->where('round_label', $currentRound)
                        ->first();

                    return (object)[
                        'registration_id' => $regId,
                        'registration' => $reg,
                        'athletes' => $athletes,
                        'score' => $score,
                        'judge_1' => $score?->judge_1 ?? 0,
                        'judge_2' => $score?->judge_2 ?? 0,
                        'judge_3' => $score?->judge_3 ?? 0,
                        'judge_4' => $score?->judge_4 ?? 0,
                        'judge_5' => $score?->judge_5 ?? 0,
                        'denda' => $score?->denda ?? 0,
                        'nilai_akhir' => $score?->nilai_akhir ?? 0,
                    ];
                })
                ->sortByDesc('nilai_akhir')
                ->values();
        }

        return view('livewire.admin.arbitrase.scoring.monitor-rekapitulasi-hasil-index', [
            'court' => $court,
            'match' => $match,
            'scores' => $scores,
            'currentRound' => $currentRound ?? null,
            'poolName' => $poolName,
        ]);
    }
}
