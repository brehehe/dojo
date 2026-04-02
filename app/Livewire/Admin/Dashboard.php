<?php

namespace App\Livewire\Admin;

use App\Models\Athlete;
use App\Models\Contingent;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            'total_contingents' => Contingent::count(),
            'total_athletes' => Athlete::count(),
            'pending_verifications' => Contingent::where('status', 'pending')->count(),
            'total_amount' => Contingent::where('status', 'confirmed')->sum('final_amount'),
        ];

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
        ])->layout('layouts.admin');
    }
}
