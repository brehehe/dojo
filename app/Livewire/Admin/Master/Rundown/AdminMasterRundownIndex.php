<?php

namespace App\Livewire\Admin\Master\Rundown;

use App\Models\Rundown\Rundown;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminMasterRundownIndex extends Component
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
    public $description;
    public $start_time;
    public $end_time;

    public $showingRundownModal = false;

    public $rundownIdBeingEdited = null;

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
        $this->reset(['name', 'description', 'start_time', 'end_time', 'rundownIdBeingEdited']);
        $this->showingRundownModal = true;
    }

    public function showEditModal($rundownId)
    {
        $this->resetValidation();
        $this->rundownIdBeingEdited = $rundownId;
        $rundown = Rundown::findOrFail($rundownId);

        // Load User Data
        $this->name = $rundown->name;
        $this->description = $rundown->description;
        $this->start_time = Carbon::parse($rundown->start_time)->format('Y-m-d H:i:s');
        $this->end_time = Carbon::parse($rundown->end_time)->format('Y-m-d H:i:s');
        $this->showingRundownModal = true;
    }

    public function saveRundown()
    {
        $rules = [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'start_time' => 'required',
            'end_time' => 'required',
        ];

        $this->validate($rules);


        DB::transaction(function () {
            if ($this->rundownIdBeingEdited) {
                $rundown = Rundown::findOrFail($this->rundownIdBeingEdited);

                $rundown->update([
                    'name' => $this->name,
                    'description' => $this->description,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Data Rundown telah diperbarui.', icon: 'success');
            } else {
                Rundown::create([
                    'name' => $this->name,
                    'description' => $this->description,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Rundown baru telah ditambahkan.', icon: 'success');
            }
        });

        $this->showingRundownModal = false;
    }

    public function deleteReferee($rundownId)
    {
        $rundown = Rundown::findOrFail($rundownId);
        DB::transaction(function () use ($rundown) {
            $rundown->delete();
        });

        $this->dispatch('swal', title: 'Dihapus!', text: 'Rundown telah dihapus.', icon: 'success');
    }

    public function render()
    {
        $rundowns = Rundown::orWhere('name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate($this->perPage === 'all' ? Rundown::count() : $this->perPage);

        return view('livewire.admin.master.rundown.admin-master-rundown-index', [
            'rundowns' => $rundowns,
        ]);
    }
}
