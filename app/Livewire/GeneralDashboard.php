<?php

namespace App\Livewire;

use Livewire\Component;

class GeneralDashboard extends Component
{
    public function mount()
    {
        $user = auth()->user();

        // If user has admin roles, they should probably go to the Admin Dashboard
        if ($user->hasAnyRole(['Super Admin', 'Admin'])) {
            return redirect()->route('admin.new-dashboard');
        }

        // Handle Contingent role
        if ($user->hasRole('Contingent')) {
            if (! $user->contingent()->exists()) {
                return redirect()->route('contingent.setup');
            }

            return redirect()->route('contingent.dashboard');
        }

        // Referee Stats
        if ($user->hasAnyRole(['Wasit', 'Perwasitan'])) {
            $this->isReferee = true;
            $this->referee = $user->referee;
            $this->assignedMatchesCount = $this->referee
                ? \App\Models\DrawingMatchNumber::query()
                    ->join('schedule_referees', function ($join) {
                        $join->on('drawing_match_numbers.rundown_id', '=', 'schedule_referees.rundown_id')
                            ->on('drawing_match_numbers.session_time_id', '=', 'schedule_referees.session_time_id')
                            ->on('drawing_match_numbers.court_id', '=', 'schedule_referees.court_id');
                    })
                    ->where('schedule_referees.referee_id', $this->referee->id)
                    ->distinct('drawing_match_numbers.match_number_id')
                    ->count('drawing_match_numbers.match_number_id')
                : 0;
        }
    }

    public $isReferee = false;

    public $referee;

    public $assignedMatchesCount = 0;

    public function render()
    {
        return view('livewire.general-dashboard')
            ->layout('layouts.premium');
    }
}
