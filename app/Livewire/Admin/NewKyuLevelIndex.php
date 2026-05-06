<?php

namespace App\Livewire\Admin;

use App\Models\KyuLevel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NewKyuLevelIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    public $name;

    public $showingKyuLevelModal = false;

    public $kyuLevelIdBeingEdited = null;

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
        $this->reset(['name', 'kyuLevelIdBeingEdited']);
        $this->showingKyuLevelModal = true;
    }

    public function showEditModal($id)
    {
        $this->resetValidation();
        $this->kyuLevelIdBeingEdited = $id;
        $model = KyuLevel::findOrFail($id);

        $this->name = $model->name;
        $this->showingKyuLevelModal = true;
    }

    public function saveKyuLevel()
    {
        $this->validate([
            'name' => 'required|min:1',
        ]);

        DB::transaction(function () {
            if ($this->kyuLevelIdBeingEdited) {
                $model = KyuLevel::findOrFail($this->kyuLevelIdBeingEdited);
                $model->update(['name' => $this->name]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Data Kyu / Dan telah diperbarui.',
                    'icon' => 'success',
                ]);
            } else {
                KyuLevel::create(['name' => $this->name]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Kyu / Dan baru telah ditambahkan.',
                    'icon' => 'success',
                ]);
            }
        });

        $this->showingKyuLevelModal = false;
    }

    public function deleteKyuLevel($id)
    {
        $model = KyuLevel::findOrFail($id);
        DB::transaction(function () use ($model) {
            $model->delete();
        });

        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Kyu / Dan telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $query = KyuLevel::query();

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        $kyuLevels = $query->latest()->paginate($this->perPage === 'all' ? KyuLevel::count() : $this->perPage);

        return view('livewire.admin.new-kyu-level-index', [
            'kyuLevels' => $kyuLevels,
        ])->layout('layouts.premium', ['title' => 'Master Kyu / Dan']);
    }
}
