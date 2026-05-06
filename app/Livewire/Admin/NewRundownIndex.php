<?php

namespace App\Livewire\Admin;

use App\Models\Rundown\Rundown;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NewRundownIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

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
        $this->reset(['name', 'description', 'date', 'rundownIdBeingEdited']);
        $this->type = 'pertandingan';
        $this->showingRundownModal = true;
    }

    public function showEditModal($id)
    {
        $this->resetValidation();
        $this->rundownIdBeingEdited = $id;
        $model = Rundown::findOrFail($id);

        $this->name = $model->name;
        $this->description = $model->description;
        $this->date = $model->date ? Carbon::parse($model->date)->format('Y-m-d\TH:i') : null;
        $this->type = $model->type ?? 'pertandingan';
        $this->showingRundownModal = true;
    }

    public function saveRundown()
    {
        $this->validate([
            'name' => 'required|max:255',
            'type' => 'required|in:pertandingan,seremonial',
            'description' => 'nullable',
            'date' => 'required',
        ]);

        DB::transaction(function () {
            if ($this->rundownIdBeingEdited) {
                $model = Rundown::findOrFail($this->rundownIdBeingEdited);
                $model->update([
                    'name' => $this->name,
                    'type' => $this->type,
                    'description' => $this->description,
                    'date' => $this->date,
                ]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Data Rundown telah diperbarui.',
                    'icon' => 'success',
                ]);
            } else {
                Rundown::create([
                    'name' => $this->name,
                    'type' => $this->type,
                    'description' => $this->description,
                    'date' => $this->date,
                ]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Rundown baru telah ditambahkan.',
                    'icon' => 'success',
                ]);
            }
        });

        $this->showingRundownModal = false;
    }

    public function deleteRundown($id)
    {
        $model = Rundown::findOrFail($id);
        DB::transaction(function () use ($model) {
            $model->delete();
        });

        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Rundown telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $query = Rundown::query();

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        $rundowns = $query->orderBy('date', 'asc')->paginate($this->perPage === 'all' ? Rundown::count() : $this->perPage);

        return view('livewire.admin.new-rundown-index', [
            'rundowns' => $rundowns,
        ])->layout('layouts.premium', ['title' => 'Master Rundown']);
    }
}
