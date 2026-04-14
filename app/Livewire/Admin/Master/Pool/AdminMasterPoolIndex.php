<?php

namespace App\Livewire\Admin\Master\Pool;

use App\Models\Pool\Pool;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminMasterPoolIndex extends Component
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

    public function showEditModal($poolId)
    {
        $this->resetValidation();
        $this->poolIdBeingEdited = $poolId;
        $pool = Pool::findOrFail($poolId);

        // Load User Data
        $this->name = $pool->name;
        $this->showingPoolModal = true;
    }

    public function savePool()
    {
        $rules = [
            'name' => 'required|max:255',
        ];

        $this->validate($rules);


        DB::transaction(function () {
            if ($this->poolIdBeingEdited) {
                $pool = Pool::findOrFail($this->poolIdBeingEdited);

                $pool->update([
                    'name' => $this->name,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Data Pool telah diperbarui.', icon: 'success');
            } else {
                Pool::create([
                    'name' => $this->name,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Pool baru telah ditambahkan.', icon: 'success');
            }
        });

        $this->showingPoolModal = false;
    }

    public function deletePool($poolId)
    {
        $pool = Pool::findOrFail($poolId);
        DB::transaction(function () use ($pool) {
            $pool->delete();
        });

        $this->dispatch('swal', title: 'Dihapus!', text: 'Pool telah dihapus.', icon: 'success');
    }

    public function render()
    {
        $pools = Pool::orWhere('name', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage === 'all' ? Pool::count() : $this->perPage);

        return view('livewire.admin.master.pool.admin-master-pool-index', [
            'pools' => $pools,
        ]);
    }
}
