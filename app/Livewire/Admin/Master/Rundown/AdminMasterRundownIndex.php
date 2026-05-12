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

    public $date;

    public $type = 'pertandingan';

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
        $this->reset(['name', 'description', 'date', 'type', 'rundownIdBeingEdited']);
        $this->type = 'pertandingan';
        $this->showingRundownModal = true;
    }

    public function showEditModal($rundownId)
    {
        $this->resetValidation();
        $this->rundownIdBeingEdited = $rundownId;
        $rundown = Rundown::findOrFail($rundownId);

        // Load Data
        $this->name = $rundown->name;
        $this->description = $rundown->description;
        $this->date = $rundown->date ? Carbon::parse($rundown->date)->format('Y-m-d\TH:i') : null;
        $this->type = $rundown->type ?? 'pertandingan';
        $this->showingRundownModal = true;
    }

    public function saveRundown()
    {
        $rules = [
            'name' => 'required|max:255',
            'type' => 'required|in:pertandingan,seremonial',
            'description' => 'nullable',
            'date' => 'required',
        ];

        $this->validate($rules);

        DB::transaction(function () {
            if ($this->rundownIdBeingEdited) {
                $rundown = Rundown::findOrFail($this->rundownIdBeingEdited);

                $rundown->update([
                    'name' => $this->name,
                    'type' => $this->type,
                    'description' => $this->description,
                    'date' => $this->date,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Data Rundown telah diperbarui.', icon: 'success');
            } else {
                Rundown::create([
                    'name' => $this->name,
                    'type' => $this->type,
                    'description' => $this->description,
                    'date' => $this->date,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Rundown baru telah ditambahkan.', icon: 'success');
            }
        });

        $this->showingRundownModal = false;
    }

    public function deleteRundown($rundownId)
    {
        $rundown = Rundown::findOrFail($rundownId);
        DB::transaction(function () use ($rundown) {
            $rundown->delete();
        });

        $this->dispatch('swal', title: 'Dihapus!', text: 'Rundown telah dihapus.', icon: 'success');
    }

    public function render()
    {
        $rundowns = Rundown::orWhere('name', 'ilike', '%'.$this->search.'%')
            ->latest()
            ->paginate($this->perPage === 'all' ? Rundown::count() : $this->perPage);

        return view('livewire.admin.master.rundown.admin-master-rundown-index', [
            'rundowns' => $rundowns,
        ]);
    }
}
