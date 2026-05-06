<?php

namespace App\Livewire\Admin;

use App\Models\Technique\Technique;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NewTechniqueIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    public $name;

    public $showingTechniqueModal = false;

    public $techniqueIdBeingEdited = null;

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
        $this->reset(['name', 'techniqueIdBeingEdited']);
        $this->showingTechniqueModal = true;
    }

    public function showEditModal($id)
    {
        $this->resetValidation();
        $this->techniqueIdBeingEdited = $id;
        $model = Technique::findOrFail($id);

        $this->name = $model->name;
        $this->showingTechniqueModal = true;
    }

    public function saveTechnique()
    {
        $this->validate([
            'name' => 'required|min:2',
        ]);

        DB::transaction(function () {
            if ($this->techniqueIdBeingEdited) {
                $model = Technique::findOrFail($this->techniqueIdBeingEdited);
                $model->update(['name' => $this->name]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Data Teknik & Jurus telah diperbarui.',
                    'icon' => 'success',
                ]);
            } else {
                Technique::create(['name' => $this->name]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Teknik & Jurus baru telah ditambahkan.',
                    'icon' => 'success',
                ]);
            }
        });

        $this->showingTechniqueModal = false;
    }

    public function deleteTechnique($id)
    {
        $model = Technique::findOrFail($id);
        DB::transaction(function () use ($model) {
            $model->delete();
        });

        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Teknik & Jurus telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $query = Technique::query();

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        $techniques = $query->latest()->paginate($this->perPage === 'all' ? Technique::count() : $this->perPage);

        return view('livewire.admin.new-technique-index', [
            'techniques' => $techniques,
        ])->layout('layouts.premium', ['title' => 'Master Teknik & Jurus']);
    }
}
