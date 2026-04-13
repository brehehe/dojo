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
            return redirect()->route('admin.dashboard');
        }

        // Handle Contingent role
        if ($user->hasRole('Contingent')) {
            if (!$user->contingent()->exists()) {
                return redirect()->route('contingent.setup');
            }
            return redirect()->route('contingent.dashboard');
        }
    }

    public function render()
    {
        return view('livewire.general-dashboard')
            ->layout('layouts.admin'); // Reusing the premium admin layout
    }
}
