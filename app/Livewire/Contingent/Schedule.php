<?php

namespace App\Livewire\Contingent;

use App\Models\DrawingMatchNumber;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Schedule extends Component
{
    public string $filterType = 'all';

    protected ?int $contingentId = null;

    public function mount(): void
    {
        $user = Auth::user();

        if (! $user->contingent()->exists()) {
            redirect()->route('contingent.setup');

            return;
        }

        $this->contingentId = $user->contingent->id;
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

        return view('livewire.contingent.schedule', [
            'contingent' => $contingent,
            'schedules' => $schedules,
        ]);
    }
}
