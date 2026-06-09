<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\ActiveCourtReferee;
use App\Models\Court\Court;
use App\Models\ScheduleReferee;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class MonitorRefereeIndex extends Component
{
    public $courtId;

    public function mount($courtId)
    {
        $this->courtId = $courtId;
    }

    public function render()
    {
        $court = Court::findOrFail($this->courtId);

        // Source of Truth is now the ActiveCourtReferee table
        $referees = ActiveCourtReferee::with('referee.user')
            ->where('court_id', $court->id)
            ->orderBy('judge_index')
            ->get();

        // Fallback: If no active referees are manually overridden, load from schedule
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

        return view('livewire.admin.arbitrase.scoring.monitor-referee-index', [
            'court' => $court,
            'referees' => $referees,
            // Header context can now be simple
            'contextRundown' => $court->activeDrawing?->rundown,
            'contextSession' => $court->activeDrawing?->sessionTime,
        ]);
    }
}
