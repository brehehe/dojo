<?php

namespace App\Livewire;

use App\Exports\ScheduleExport;
use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

#[Layout('layouts.guest')]
class PublicSchedule extends Component
{
    public string $search = '';

    public string $filterType = 'all';

    public string $filterCourt = '';

    public function updated($property): void
    {
        // No pagination reset needed since we retrieve all or group in PHP, but let's be consistent
    }

    public function export()
    {
        return Excel::download(new ScheduleExport, 'jadwal_pertandingan_'.now()->format('Ymd_His').'.xlsx');
    }

    public function render()
    {
        $query = DrawingMatchNumber::with([
            'matchNumber.ageGroup',
            'registration.contingent',
            'court',
            'pool',
            'rundown',
            'sessionTime',
        ]);

        if ($this->filterType !== 'all') {
            $query->where('draft_type', $this->filterType);
        }

        if (! empty($this->filterCourt)) {
            $query->where('court_id', $this->filterCourt);
        }

        if (! empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('matchNumber', fn ($mq) => $mq->where('name', 'ilike', '%'.$search.'%'))
                    ->orWhereHas('registration.contingent', fn ($cq) => $cq->where('name', 'ilike', '%'.$search.'%'))
                    ->orWhereHas('registration.athletes', fn ($aq) => $aq->where('name', 'ilike', '%'.$search.'%'));
            });
        }

        $drawings = $query->get()
            ->sortBy(function ($d) {
                $date = $d->rundown?->date ? $d->rundown->date->format('Y-m-d') : '9999-12-31';
                $sTime = $d->sessionTime?->start_time ? Carbon::parse($d->sessionTime->start_time)->format('H:i') : '99:99';
                $mTime = $d->metadata['start_time'] ?? '99:99';
                $seq = $d->sequence_number ?? 9999;

                return $date.' '.$sTime.' '.$mTime.' '.str_pad($seq, 4, '0', STR_PAD_LEFT);
            });

        // Group by Date for display
        $schedules = $drawings->groupBy(fn ($d) => $d->rundown?->date ? $d->rundown->date->format('Y-m-d') : 'Belum Dijadwalkan');

        return view('livewire.public-schedule', [
            'schedules' => $schedules,
            'courts' => Court::orderBy('order')->get(),
        ])->title('Jadwal Pertandingan | Smart-Perkemi');
    }
}
