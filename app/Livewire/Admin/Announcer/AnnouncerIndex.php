<?php

namespace App\Livewire\Admin\Announcer;

use App\Models\Court\Court;
use App\Models\DrawingMatchNumber;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AnnouncerIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $filterCourt = '';

    public $customMessage = '';

    protected $updatesQueryString = ['search', 'filterCourt'];

    public function callParticipant($id)
    {
        $drawing = DrawingMatchNumber::with([
            'matchNumber.ageGroup',
            'registration.contingent',
            'registration.athletes',
            'court',
            'pool',
        ])->findOrFail($id);

        $athletes = $drawing->registration->athletes->pluck('name')->implode(', ');
        $contingent = $drawing->registration->contingent->name;
        $matchName = $drawing->matchNumber->name;
        $courtName = $drawing->court ? $drawing->court->name : 'lapangan yang ditentukan';
        $poolName = $drawing->pool ? ' Pool '.$drawing->pool->name : '';

        $text = "Panggilan untuk kontingen {$contingent}. Atas nama {$athletes}. Silakan menuju {$courtName}. Untuk kategori {$matchName}{$poolName}. Sekali lagi, panggilan untuk kontingen {$contingent}. Atas nama {$athletes}. Silakan menuju {$courtName}. Terima kasih.";

        $this->dispatch('play-announcer', ['text' => $text]);
    }

    public function playCustom()
    {
        if (empty($this->customMessage)) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Pesan Kosong',
                'text' => 'Silakan masukkan pesan yang ingin diumumkan.',
            ]);

            return;
        }

        $this->dispatch('play-announcer', ['text' => $this->customMessage]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterCourt()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = DrawingMatchNumber::with([
            'matchNumber.ageGroup',
            'registration.contingent',
            'registration.athletes',
            'court',
            'pool',
        ])->whereNotNull('match_number_id');

        if ($this->search) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('registration.contingent', fn ($sq) => $sq->where('name', 'ilike', '%'.$search.'%'))
                    ->orWhereHas('registration.athletes', fn ($sq) => $sq->where('name', 'ilike', '%'.$search.'%'))
                    ->orWhereHas('matchNumber', fn ($sq) => $sq->where('name', 'ilike', '%'.$search.'%'));
            });
        }

        if ($this->filterCourt) {
            $query->where('court_id', $this->filterCourt);
        }

        $drawings = $query->latest()->paginate(20);
        $courts = Court::all();

        return view('livewire.admin.announcer.announcer-index', [
            'drawings' => $drawings,
            'courts' => $courts,
        ]);
    }
}
