<?php

namespace App\Livewire\Contingent;

use App\Models\DrawingMatchNumber;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.premium')]
class Schedule extends Component
{
    public string $filterType = 'all';

    public string $activeTab = 'schedule'; // schedule, bracket

    public ?int $selectedBracketMatchId = null;

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user->contingent()->exists()) {
            redirect()->route('contingent.setup');

            return;
        }
    }

    public function render()
    {
        $contingent = Auth::user()->contingent;

        $registrationIds = Registration::where('contingent_id', $contingent->id)
            ->pluck('id')
            ->toArray();

        $query = DrawingMatchNumber::whereIn('registration_id', $registrationIds)
            ->with([
                'matchNumber.ageGroup',
                'registration.contingent',
                'registration.athletes',
                'court',
                'pool',
                'rundown',
                'sessionTime',
            ])
            ->orderBy('schedule_date')
            ->orderBy('sequence_number');

        if ($this->filterType !== 'all') {
            $query->where('draft_type', $this->filterType);
        }

        $schedules = $query->get()->groupBy(fn ($d) => $d->schedule_date ?? 'Belum Dijadwalkan');

        // Fetch Randori match numbers that this contingent's registered athletes participate in
        $matchNumberIds = DB::table('athlete_match_number')
            ->whereIn('registration_id', $registrationIds)
            ->pluck('match_number_id')
            ->unique()
            ->toArray();

        $randoriMatchNumbers = MatchNumber::whereIn('id', $matchNumberIds)
            ->where('draft_type', 'randori')
            ->whereNotNull('drawing_generated_at')
            ->with('ageGroup')
            ->get();

        if ($this->selectedBracketMatchId === null && $randoriMatchNumbers->isNotEmpty()) {
            $this->selectedBracketMatchId = $randoriMatchNumbers->first()->id;
        }

        $selectedBracketMatch = $this->selectedBracketMatchId
            ? MatchNumber::with('ageGroup')->find($this->selectedBracketMatchId)
            : null;

        return view('livewire.contingent.schedule', [
            'contingent' => $contingent,
            'schedules' => $schedules,
            'randoriMatchNumbers' => $randoriMatchNumbers,
            'selectedBracketMatch' => $selectedBracketMatch,
        ]);
    }
}
