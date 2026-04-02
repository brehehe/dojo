<?php

namespace App\Livewire\Admin;

use App\Models\Contingent;
use Livewire\Component;
use Livewire\WithPagination;

class ContingentIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public string $statusFilter = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Contingent::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('kab_kota', 'like', '%'.$this->search.'%')
                    ->orWhere('leader_name', 'like', '%'.$this->search.'%');
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->latest();

        return view('livewire.admin.contingent-index', [
            'contingents' => $query->paginate(10),
        ])->layout('layouts.admin');
    }
}
