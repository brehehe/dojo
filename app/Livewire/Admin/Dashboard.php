<?php

namespace App\Livewire\Admin;

use App\Models\Athlete;
use App\Models\Registration;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_contingents' => Registration::count(),
            'total_athletes' => Athlete::count(),
            'pending_verifications' => Registration::where('status', 'pending')->count(),
            'total_amount' => Registration::where('status', 'confirmed')->sum('final_amount'),
        ];

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
        ])->layout('layouts.admin');
    }
}
