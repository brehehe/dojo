<?php

namespace App\Livewire\Contingent;

use App\Models\Contingent;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')] // Reusing premium admin layout for consistent UX
class Dashboard extends Component
{
    public $contingent;
    public $registrations;

    public function mount()
    {
        $user = Auth::user();
        
        // Redirect if profile doesn't exist
        if (!$user->contingent()->exists()) {
            return redirect()->route('contingent.setup');
        }

        $this->contingent = $user->contingent;
        $this->registrations = Registration::where('contingent_id', $this->contingent->id)
            ->withCount(['athletes', 'officials'])
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.contingent.dashboard');
    }
}
