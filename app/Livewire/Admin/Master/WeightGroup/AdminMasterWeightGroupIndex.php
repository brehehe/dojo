<?php

namespace App\Livewire\Admin\Master\WeightGroup;

use App\Models\Group\WeightGroup;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminMasterWeightGroupIndex extends Component
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

    public function showEditModal($weightGroupId)
    {
        $this->resetValidation();
        $this->weightGroupIdBeingEdited = $weightGroupId;
        $weightGroup = WeightGroup::findOrFail($weightGroupId);

        // Load User Data
        $this->name = $weightGroup->name;
        $this->showingWeightGroupModal = true;
    }

    public function saveWeightGroup()
    {
        $rules = [
            'name' => 'required|min:3',
        ];

        $this->validate($rules);

        DB::transaction(function () {
            if ($this->weightGroupIdBeingEdited) {
                $weightGroup = WeightGroup::findOrFail($this->weightGroupIdBeingEdited);

                $weightGroup->update([
                    'name' => $this->name,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Data Kelompok Berat Badan telah diperbarui.', icon: 'success');
            } else {
                WeightGroup::create([
                    'name' => $this->name,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Kelompok Berat Badan baru telah ditambahkan.', icon: 'success');
            }
        });

        $this->showingWeightGroupModal = false;
    }

    public function deleteReferee($weightGroupId)
    {
        $weightGroup = WeightGroup::findOrFail($weightGroupId);
        DB::transaction(function () use ($weightGroup) {
            $weightGroup->delete();
        });

        $this->dispatch('swal', title: 'Dihapus!', text: 'Kelompok Berat Badan dan akun login terkait telah dihapus.', icon: 'success');
    }

    public function render()
    {
        $weightGroups = WeightGroup::orWhere('name', 'ilike', '%'.$this->search.'%')
            ->latest()
            ->paginate($this->perPage === 'all' ? WeightGroup::count() : $this->perPage);

        return view('livewire.admin.master.weight-group.admin-master-weight-group-index', [
            'weightGroups' => $weightGroups,
        ]);
    }
}
