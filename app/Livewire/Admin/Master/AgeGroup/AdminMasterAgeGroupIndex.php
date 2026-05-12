<?php

namespace App\Livewire\Admin\Master\AgeGroup;

use App\Models\Group\AgeGroup;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminMasterAgeGroupIndex extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'livewire.admin.pagination';
    }

    public $search = '';

    public $perPage = 5;

    // User Fields
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

        // Load User Data
        $this->name = $ageGroup->name;
        $this->price = (float) $ageGroup->price;
        $this->showingAgeGroupModal = true;
    }

    public function saveAgeGroup()
    {
        $rules = [
            'name' => 'required|min:3',
            'price' => 'required|numeric',
        ];

        $this->validate($rules);

        DB::transaction(function () {
            if ($this->ageGroupIdBeingEdited) {
                $ageGroup = AgeGroup::findOrFail($this->ageGroupIdBeingEdited);

                $ageGroup->update([
                    'name' => $this->name,
                    'price' => $this->price,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Data Kelompok Umur telah diperbarui.', icon: 'success');
            } else {
                AgeGroup::create([
                    'name' => $this->name,
                    'price' => $this->price,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Kelompok Umur baru telah ditambahkan.', icon: 'success');
            }
        });

        $this->showingAgeGroupModal = false;
    }

    public function deleteReferee($ageGroupId)
    {
        $ageGroup = AgeGroup::findOrFail($ageGroupId);
        DB::transaction(function () use ($ageGroup) {
            $ageGroup->delete();
        });

        $this->dispatch('swal', title: 'Dihapus!', text: 'Kelompok Umur dan akun login terkait telah dihapus.', icon: 'success');
    }

    public function render()
    {
        $ageGroups = AgeGroup::orWhere('name', 'ilike', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage === 'all' ? AgeGroup::count() : $this->perPage);

        return view('livewire.admin.master.age-group.admin-master-age-group-index', [
            'ageGroups' => $ageGroups,
        ]);
    }
}
