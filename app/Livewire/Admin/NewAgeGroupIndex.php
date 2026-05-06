<?php

namespace App\Livewire\Admin;

use App\Models\Group\AgeGroup;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NewAgeGroupIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    // Fields
    public $name;

    public $price = 0;

    public $showingAgeGroupModal = false;

    public $ageGroupIdBeingEdited = null;

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
        $this->reset(['name', 'price', 'ageGroupIdBeingEdited']);
        $this->showingAgeGroupModal = true;
    }

    public function showEditModal($ageGroupId)
    {
        $this->resetValidation();
        $this->ageGroupIdBeingEdited = $ageGroupId;
        $ageGroup = AgeGroup::findOrFail($ageGroupId);

        $this->name = $ageGroup->name;
        $this->price = (float) $ageGroup->price;
        $this->showingAgeGroupModal = true;
    }

    public function saveAgeGroup()
    {
        $this->validate([
            'name' => 'required|min:3',
            'price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () {
            if ($this->ageGroupIdBeingEdited) {
                $ageGroup = AgeGroup::findOrFail($this->ageGroupIdBeingEdited);

                $ageGroup->update([
                    'name' => $this->name,
                    'price' => $this->price,
                ]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Data Kelompok Umur telah diperbarui.',
                    'icon' => 'success',
                ]);
            } else {
                AgeGroup::create([
                    'name' => $this->name,
                    'price' => $this->price,
                ]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Kelompok Umur baru telah ditambahkan.',
                    'icon' => 'success',
                ]);
            }
        });

        $this->showingAgeGroupModal = false;
    }

    public function deleteAgeGroup($ageGroupId)
    {
        $ageGroup = AgeGroup::findOrFail($ageGroupId);
        DB::transaction(function () use ($ageGroup) {
            $ageGroup->delete();
        });

        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Kelompok Umur telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $query = AgeGroup::query();

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        $ageGroups = $query->latest()->paginate($this->perPage === 'all' ? AgeGroup::count() : $this->perPage);

        return view('livewire.admin.new-age-group-index', [
            'ageGroups' => $ageGroups,
        ])->layout('layouts.premium', ['title' => 'Master Kelompok Umur']);
    }
}
