<?php

namespace App\Livewire\Admin\Master\Athlete;

use App\Models\Athlete;
use App\Models\Contingent;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminMasterAthleteIndex extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'livewire.admin.pagination';
    }

    public $search = '';

    public $perPage = 5;

    public $filterContingent = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
        'filterContingent' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterContingent()
    {
        $this->resetPage();
    }

    public function deleteAthlete(Athlete $athlete)
    {
        $athlete->delete();
        $this->dispatch('swal', title: 'Berhasil!', text: 'Data atlet berhasil dihapus.', icon: 'success');
    }

    public function render()
    {
        $athletes = Athlete::with(['contingents', 'categories'])
            ->when($this->search, function ($query) {
                $query->where('name', 'ilike', '%'.$this->search.'%')
                    ->orWhere('nik', 'ilike', '%'.$this->search.'%');
            })
            ->when($this->filterContingent, function ($query) {
                $query->whereHas('contingents', function ($q) {
                    $q->where('contingent_id', $this->filterContingent);
                });
            })
            ->when(auth()->user()->hasRole('Contingent'), function ($query) {
                $query->whereHas('contingents', function ($q) {
                    $q->where('contingent_id', auth()->user()->contingent?->id)
                      ->where('athlete_contingent.is_primary', true);
                });
            })
            ->latest()
            ->paginate($this->perPage === 'all' ? Athlete::count() : $this->perPage);

        $contingents = Contingent::orderBy('name')->get();

        return view('livewire.admin.master.athlete.admin-master-athlete-index', [
            'athletes' => $athletes,
            'contingents' => $contingents,
        ]);
    }
}
