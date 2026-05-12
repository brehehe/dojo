<?php

namespace App\Livewire\Admin\Master\Official;

use App\Models\Contingent;
use App\Models\Official;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminMasterOfficialIndex extends Component
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

    public function deleteOfficial(Official $official)
    {
        $official->delete();
        $this->dispatch('swal', title: 'Berhasil!', text: 'Data official berhasil dihapus.', icon: 'success');
    }

    public function render()
    {
        $officials = Official::when($this->search, function ($query) {
            $query->where('name', 'ilike', '%' . $this->search . '%')
                ->orWhere('phone', 'ilike', '%' . $this->search . '%');
        })
            ->when($this->filterContingent, function ($query) {
                $query->where('contingent_id', $this->filterContingent);
            })
            ->when(auth()->user()->hasRole('Contingent'), function ($query) {
                $query->where('contingent_id', auth()->user()->contingent?->id);
            })
            ->latest()
            ->paginate($this->perPage === 'all' ? Official::count() : $this->perPage);

        $contingents = Contingent::orderBy('name')->get();

        return view('livewire.admin.master.official.admin-master-official-index', [
            'officials' => $officials,
            'contingents' => $contingents,
        ]);
    }
}
