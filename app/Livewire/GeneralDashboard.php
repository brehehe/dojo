<?php

namespace App\Livewire;

use Livewire\Component;

class GeneralDashboard extends Component
{
    public function mount()
    {
        // If user has admin roles, they should probably go to the Admin Dashboard
        if (auth()->user()->hasAnyRole(['Super Admin', 'Admin Pendaftaran'])) {
            return redirect()->route('admin.dashboard');
        }
    }

    public function render()
    {
        return view('livewire.general-dashboard')
            ->layout('layouts.admin'); // Reusing the premium admin layout
    }
}
