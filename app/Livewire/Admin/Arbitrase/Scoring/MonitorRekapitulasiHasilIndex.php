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
            
            $matchNumberIds = [$match->id];
            if ($match->mergeDetail) {
                $matchNumberIds = \App\Models\MatchNumberMergeDetail::where('match_number_merge_id', $match->mergeDetail->match_number_merge_id)
                    ->pluck('match_number_id')
                    ->toArray();
            }

            $drawQuery = \App\Models\DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)
                ->where('draft_type', 'embu');

            $currentRound = 'Penyisihan';
            $validActiveDrawing = $activeDrawing && in_array($activeDrawing->match_number_id, $matchNumberIds);

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
                $firstDrawingOnCourt = \App\Models\DrawingMatchNumber::whereIn('match_number_id', $matchNumberIds)
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

            $drawings = $drawQuery->get();
            $drawingRegIds = $drawings->pluck('registration_id')->unique()->filter()->toArray();

            // Eager load registrations
            $registrations = Registration::with(['contingent', 'athletes'])->whereIn('id', $drawingRegIds)->get()->keyBy('id');

            $scores = $drawings->map(function($drawing) use ($matchNumberIds, $currentRound, $registrations) {
                $regId = $drawing->registration_id;
                $reg = $registrations->get($regId);
                $specificMatchId = $drawing->match_number_id;
                
                // Correctly filter athletes for this specific team/drawing
                $athleteIds = $drawing->metadata['athlete_ids'] ?? [];
                $athletes = collect();
                if (!empty($athleteIds)) {
                    $athletes = $reg?->athletes->whereIn('id', $athleteIds)->values() ?? collect();
                } elseif ($reg) {
                    $athletes = $reg->athletes;
                }

                $matchRecord = \App\Models\MatchNumber\MatchNumber::find($specificMatchId);

                $score = EmbuScore::where('match_number_id', $specificMatchId)
                    ->where('registration_id', $regId)
                    ->where('round_label', $currentRound)
                    ->first();

                return (object)[
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
