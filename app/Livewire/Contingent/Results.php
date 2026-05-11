<?php

namespace App\Livewire\Contingent;

use App\Models\Registration;
use App\Models\TournamentResult;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.premium')]
class Results extends Component
{
    public string $filterType = 'all';

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user->contingent()->exists()) {
            redirect()->route('contingent.setup');
        }
    }

    public function render()
    {
        $contingent = Auth::user()->contingent;

        $registrationIds = Registration::where('contingent_id', $contingent->id)
            ->pluck('id')
            ->toArray();

        $results = TournamentResult::where('contingent_name', $contingent->name)
            ->with(['matchNumber.ageGroup'])
            ->when($this->filterType !== 'all', fn ($q) => $q->where('draft_type', $this->filterType))
            ->orderBy('draft_type')
            ->orderBy('match_number_id')
            ->orderBy('rank')
            ->get();

        return view('livewire.contingent.results', [
            'contingent' => $contingent,
            'results' => $results,
        ]);
    }
}
