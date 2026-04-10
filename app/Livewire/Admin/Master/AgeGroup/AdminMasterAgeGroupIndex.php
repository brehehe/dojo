<?php

namespace App\Livewire\Admin\Master\AgeGroup;

use App\Models\Group\AgeGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

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

    public $showingageGroupModal = false;
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
        $this->reset(['name', 'ageGroupIdBeingEdited']);
        $this->showingageGroupModal = true;
    }

    public function showEditModal($ageGroupId)
    {
        $this->resetValidation();
        $this->ageGroupIdBeingEdited = $ageGroupId;
        $ageGroup = AgeGroup::findOrFail($ageGroupId);

        // Load User Data
        $this->name = $ageGroup->name;
        $this->showingageGroupModal = true;
    }

    public function saveAgeGroup()
    {
        $rules = [
            'name' => 'required|min:3',
        ];

        $this->validate($rules);

        DB::transaction(function () {
            if ($this->ageGroupIdBeingEdited) {
                $ageGroup = AgeGroup::findOrFail($this->ageGroupIdBeingEdited);

                $ageGroup->update([
                    'name' => $this->name,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Data wasit telah diperbarui.', icon: 'success');
            } else {
                AgeGroup::create([
                    'name' => $this->name,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Wasit baru telah ditambahkan.', icon: 'success');
            }
        });

        $this->showingageGroupModal = false;
    }

    public function deleteReferee($ageGroupId)
    {
        $ageGroup = AgeGroup::findOrFail($ageGroupId);
        DB::transaction(function () use ($ageGroup) {
            $ageGroup->delete();
        });

        $this->dispatch('swal', title: 'Dihapus!', text: 'Wasit dan akun login terkait telah dihapus.', icon: 'success');
    }

    public function render()
    {
        $ageGroups = AgeGroup::orWhere('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage === 'all' ? AgeGroup::count() : $this->perPage);

        return view('livewire.admin.master.age-group.admin-master-age-group-index', [
            'ageGroups' => $ageGroups
        ]);
    }
}
