<?php

namespace App\Livewire\Admin;

use App\Models\Group\WeightGroup;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NewWeightGroupIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    public $name;

    public $showingWeightGroupModal = false;

    public $weightGroupIdBeingEdited = null;

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
        $this->reset(['name', 'weightGroupIdBeingEdited']);
        $this->showingWeightGroupModal = true;
    }

    public function showEditModal($id)
    {
        $this->resetValidation();
        $this->weightGroupIdBeingEdited = $id;
        $model = WeightGroup::findOrFail($id);

        $this->name = $model->name;
        $this->showingWeightGroupModal = true;
    }

    public function saveWeightGroup()
    {
        $this->validate([
            'name' => 'required|min:2',
        ]);

        DB::transaction(function () {
            if ($this->weightGroupIdBeingEdited) {
                $model = WeightGroup::findOrFail($this->weightGroupIdBeingEdited);
                $model->update(['name' => $this->name]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Data Kelompok Berat Badan telah diperbarui.',
                    'icon' => 'success',
                ]);
            } else {
                WeightGroup::create(['name' => $this->name]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Kelompok Berat Badan baru telah ditambahkan.',
                    'icon' => 'success',
                ]);
            }
        });

        $this->showingWeightGroupModal = false;
    }

    public function deleteWeightGroup($id)
    {
        $model = WeightGroup::findOrFail($id);
        DB::transaction(function () use ($model) {
            $model->delete();
        });

        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Kelompok Berat Badan telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $query = WeightGroup::query();

        if ($this->search) {
            $query->where('name', 'ilike', '%'.$this->search.'%');
        }

        $weightGroups = $query->latest()->paginate($this->perPage === 'all' ? WeightGroup::count() : $this->perPage);

        return view('livewire.admin.new-weight-group-index', [
            'weightGroups' => $weightGroups,
        ])->layout('layouts.premium', ['title' => 'Master Kelompok Berat']);
    }
}
