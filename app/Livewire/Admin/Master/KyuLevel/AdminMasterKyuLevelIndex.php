<?php

namespace App\Livewire\Admin\Master\KyuLevel;

use App\Models\KyuLevel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.admin')]
class AdminMasterKyuLevelIndex extends Component
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

    public $showingkyuLevelModal = false;
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
        $this->showingkyuLevelModal = true;
    }

    public function showEditModal($kyuLevelId)
    {
        $this->resetValidation();
        $this->kyuLevelIdBeingEdited = $kyuLevelId;
        $kyuLevel = KyuLevel::findOrFail($kyuLevelId);

        // Load User Data
        $this->name = $kyuLevel->name;
        $this->showingkyuLevelModal = true;
    }

    public function saveKyuLevel()
    {
        $rules = [
            'name' => 'required|min:3',
        ];

        $this->validate($rules);

        DB::transaction(function () {
            if ($this->kyuLevelIdBeingEdited) {
                $kyuLevel = KyuLevel::findOrFail($this->kyuLevelIdBeingEdited);

                $kyuLevel->update([
                    'name' => $this->name,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Data wasit telah diperbarui.', icon: 'success');
            } else {
                KyuLevel::create([
                    'name' => $this->name,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Wasit baru telah ditambahkan.', icon: 'success');
            }
        });

        $this->showingkyuLevelModal = false;
    }

    public function deleteReferee($kyuLevelId)
    {
        $kyuLevel = KyuLevel::findOrFail($kyuLevelId);
        DB::transaction(function () use ($kyuLevel) {
            $kyuLevel->delete();
        });

        $this->dispatch('swal', title: 'Dihapus!', text: 'Wasit dan akun login terkait telah dihapus.', icon: 'success');
    }

    public function render()
    {
        $kyuLevels = KyuLevel::orWhere('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage === 'all' ? KyuLevel::count() : $this->perPage);

        return view('livewire.admin.master.kyu-level.admin-master-kyu-level-index', [
            'kyuLevels' => $kyuLevels
        ]);
    }
}
