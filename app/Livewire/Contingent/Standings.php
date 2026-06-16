<?php

namespace App\Livewire\Contingent;

use App\Models\EmbuScore;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.premium')]
class Standings extends Component
{
    #[Url]
    public string $filterType = 'embu';

    #[Url]
    public string $roundFilter = 'Penyisihan';

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

        $standings = collect([]);

        if ($this->filterType === 'embu') {
            $matchNumberIds = MatchNumber::where('draft_type', 'embu')
                ->whereHas('drawings', fn ($q) => $q->whereIn('registration_id', $registrationIds))
                ->pluck('id');

            $query = EmbuScore::whereIn('match_number_id', $matchNumberIds)
                ->with(['matchNumber.ageGroup', 'registration.contingent'])
                ->where('round_label', $this->roundFilter);

            $standings = $query->orderBy('match_number_id')
                ->orderBy('nilai_akhir', 'desc')
                ->orderByRaw('COALESCE(rank, 999999) ASC')
                ->get()
                ->groupBy('match_number_id');
        } else {
            $standings = MatchNumber::where('draft_type', 'randori')
                ->whereHas('drawings', fn ($q) => $q->whereIn('registration_id', $registrationIds))
                ->with([
                    'ageGroup',
                    'randoriResults' => fn ($q) => $q->with('winner')->orderBy('bracket_node'),
                    'drawings' => fn ($q) => $q->with(['registration.contingent', 'pool']),
                ])
                ->get();
        }

        return view('livewire.contingent.standings', [
            'contingent' => $contingent,
            'standings' => $standings,
            'registrationIds' => $registrationIds,
        ]);
    }
}
