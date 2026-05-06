<?php

namespace App\Livewire\Admin;

use App\Models\Pool\Pool;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NewPoolIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    public $name;

    public $showingPoolModal = false;

    public $poolIdBeingEdited = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function showCreateModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'poolIdBeingEdited']);
        $this->showingPoolModal = true;
    }

    public function showEditModal($id)
    {
        $this->resetValidation();
        $this->poolIdBeingEdited = $id;
        $model = Pool::findOrFail($id);

        $this->name = $model->name;
        $this->showingPoolModal = true;
    }

    public function savePool()
    {
        $this->validate([
            'name' => 'required|max:255',
        ]);

        DB::transaction(function () {
            if ($this->poolIdBeingEdited) {
                $model = Pool::findOrFail($this->poolIdBeingEdited);
                $model->update(['name' => $this->name]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Data Pool telah diperbarui.',
                    'icon' => 'success',
                ]);
            } else {
                Pool::create(['name' => $this->name]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Pool baru telah ditambahkan.',
                    'icon' => 'success',
                ]);
            }
        });

        $this->showingPoolModal = false;
    }

    public function deletePool($id)
    {
        $model = Pool::findOrFail($id);
        DB::transaction(function () use ($model) {
            $model->delete();
        });

        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Pool telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $query = Pool::query();

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        $pools = $query->latest()->paginate($this->perPage === 'all' ? Pool::count() : $this->perPage);

        return view('livewire.admin.new-pool-index', [
            'pools' => $pools,
        ])->layout('layouts.premium', ['title' => 'Master Pool (Bagan)']);
    }
}
