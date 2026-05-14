<?php

namespace App\Livewire\Admin\Arbitrase\Scoring;

use App\Models\Court\Court;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class MonitorCourtIndex extends Component
{
    public $courtId;

    public function mount($courtId)
    {
        $this->courtId = $courtId;
    }

    public function getTimerState()
    {
        return \Illuminate\Support\Facades\Cache::get("court_{$this->courtId}_timer", [
            'status' => 'stopped',
            'elapsed_ms' => 0,
            'started_at_ms' => null
        ]);
    }

    public function render()
    {
        $court = Court::with([
            // Existing relations for match display
            'activeMatch.athletes.registrations.contingent',
            'activeMatch.drawings',
            'activeMatch.ageGroup',
            // New: load active drawing with full scheduling context
            'activeDrawing.matchNumber.ageGroup',
            'activeDrawing.registration.athletes',
            'activeDrawing.registration.contingent',
            'activeDrawing.pool',
            'activeDrawing.sessionTime',
            'activeDrawing.rundown',
            'activeDrawing.court',
        ])->findOrFail($this->courtId);

        return view('livewire.admin.arbitrase.scoring.monitor-court-index', [
            'court' => $court,
        ]);
    }
}
