<?php

namespace App\Livewire\Admin;

use App\Models\SessionTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NewSessionTimeIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    public $name;

    public $start_time;

    public $end_time;

    public $showingSessionTimeModal = false;

    public $sessionTimeIdBeingEdited = null;

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
        $this->reset(['name', 'start_time', 'end_time', 'sessionTimeIdBeingEdited']);
        $this->showingSessionTimeModal = true;
    }

    public function showEditModal($id)
    {
        $this->resetValidation();
        $this->sessionTimeIdBeingEdited = $id;
        $model = SessionTime::findOrFail($id);

        $this->name = $model->name;
        $this->start_time = $model->start_time ? Carbon::parse($model->start_time)->format('H:i') : null;
        $this->end_time = $model->end_time ? Carbon::parse($model->end_time)->format('H:i') : null;
        $this->showingSessionTimeModal = true;
    }

    public function saveSessionTime()
    {
        $this->validate([
            'name' => 'required|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        DB::transaction(function () {
            if ($this->sessionTimeIdBeingEdited) {
                $model = SessionTime::findOrFail($this->sessionTimeIdBeingEdited);
                $model->update([
                    'name' => $this->name,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                ]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Data Waktu Sesi (Session Time) telah diperbarui.',
                    'icon' => 'success',
                ]);
            } else {
                SessionTime::create([
                    'name' => $this->name,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                ]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Waktu Sesi (Session Time) baru telah ditambahkan.',
                    'icon' => 'success',
                ]);
            }
        });

        $this->showingSessionTimeModal = false;
    }

    public function deleteSessionTime($id)
    {
        $model = SessionTime::findOrFail($id);
        DB::transaction(function () use ($model) {
            $model->delete();
        });

        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Waktu Sesi (Session Time) telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $query = SessionTime::query();

        if ($this->search) {
            $query->where('name', 'like', '%'.$this->search.'%');
        }

        $sessionTimes = $query->latest()->paginate($this->perPage === 'all' ? SessionTime::count() : $this->perPage);

        return view('livewire.admin.new-session-time-index', [
            'sessionTimes' => $sessionTimes,
        ])->layout('layouts.premium', ['title' => 'Master Waktu Sesi']);
    }
}
