<?php

namespace App\Livewire\Admin;

use App\Models\Contingent;
use App\Models\Official;
use Livewire\Component;
use Livewire\WithPagination;

class NewOfficialIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

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

    public function deleteOfficial($id)
    {
        $official = Official::findOrFail($id);
        $official->delete();

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Data official berhasil dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $officials = Official::when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('name', 'ilike', '%'.$this->search.'%')
                    ->orWhere('phone', 'ilike', '%'.$this->search.'%');
            });
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

        return view('livewire.admin.new-official-index', [
            'officials' => $officials,
            'contingents' => $contingents,
        ])->layout('layouts.premium', ['title' => 'Master Official']);
    }
}
