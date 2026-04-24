<?php

namespace App\Livewire\Contingent;

use App\Models\EmbuChampion;
use App\Models\MatchNumber\MatchNumber;
use App\Models\RandoriMatchResult;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
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

        $embuResults = collect([]);
        $randoriResults = collect([]);

        if ($this->filterType === 'all' || $this->filterType === 'embu') {
            $embuResults = EmbuChampion::whereIn('registration_id', $registrationIds)
                ->with(['matchNumber.ageGroup', 'registration.contingent'])
                ->orderBy('match_number_id')
                ->orderBy('rank')
                ->get();
        }

        if ($this->filterType === 'all' || $this->filterType === 'randori') {
            $randoriMatchNumberIds = MatchNumber::where('draft_type', 'randori')
                ->whereHas('drawings', fn ($q) => $q->whereIn('registration_id', $registrationIds))
                ->pluck('id');

            $randoriResults = RandoriMatchResult::whereIn('match_number_id', $randoriMatchNumberIds)
                ->with(['matchNumber.ageGroup', 'winner'])
                ->orderBy('match_number_id')
                ->orderBy('bracket_node')
                ->get();
        }

        return view('livewire.contingent.results', [
            'contingent' => $contingent,
            'embuResults' => $embuResults,
            'randoriResults' => $randoriResults,
        ]);
    }
}
