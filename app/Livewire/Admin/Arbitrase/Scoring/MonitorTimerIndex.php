<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\Court\Court;
use Livewire\Attributes\Layout;
use Livewire\Component;

class MonitorTimerIndex extends Component
{
    public Court $court;

    public function mount($courtId)
    {
        $this->court = Court::with(['activeMatch.ageGroup', 'activeDrawing.registration.contingent'])->findOrFail($courtId);
    }

    public function getTimerState()
    {
        return \Illuminate\Support\Facades\Cache::get("court_{$this->court->id}_timer", [
            'status' => 'stopped',
            'elapsed_ms' => 0,
            'started_at_ms' => null
        ]);
    }

    #[Layout('layouts.guest')]
    public function render()
    {
        $this->court->load(['activeMatch.ageGroup', 'activeDrawing.registration.contingent']);
        return view('livewire.admin.arbitrase.scoring.monitor-timer-index');
    }
}
