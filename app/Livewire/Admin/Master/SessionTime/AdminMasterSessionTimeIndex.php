<?php

namespace App\Livewire\Admin\Master\SessionTime;

use App\Models\SessionTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminMasterSessionTimeIndex extends Component
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

    public function showEditModal($sessionTimeId)
    {
        $this->resetValidation();
        $this->sessionTimeIdBeingEdited = $sessionTimeId;
        $sessionTime = SessionTime::findOrFail($sessionTimeId);

        // Load Data
        $this->name = $sessionTime->name;
        $this->start_time = $sessionTime->start_time ? Carbon::parse($sessionTime->start_time)->format('H:i') : null;
        $this->end_time = $sessionTime->end_time ? Carbon::parse($sessionTime->end_time)->format('H:i') : null;
        $this->showingSessionTimeModal = true;
    }

    public function saveSessionTime()
    {
        $rules = [
            'name' => 'required|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
        ];

        $this->validate($rules);

        DB::transaction(function () {
            if ($this->sessionTimeIdBeingEdited) {
                $sessionTime = SessionTime::findOrFail($this->sessionTimeIdBeingEdited);

                $sessionTime->update([
                    'name' => $this->name,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Data Session Time telah diperbarui.', icon: 'success');
            } else {
                SessionTime::create([
                    'name' => $this->name,
                    'start_time' => $this->start_time,
                    'end_time' => $this->end_time,
                ]);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Session Time baru telah ditambahkan.', icon: 'success');
            }
        });

        $this->showingSessionTimeModal = false;
    }

    public function deleteSessionTime($sessionTimeId)
    {
        $sessionTime = SessionTime::findOrFail($sessionTimeId);
        DB::transaction(function () use ($sessionTime) {
            $sessionTime->delete();
        });
        $this->showingSessionTimeModal = false;
        $this->dispatch('swal', title: 'Dihapus!', text: 'SessionTime telah dihapus.', icon: 'success');
    }

    public function render()
    {
        $sessionTimes = SessionTime::orWhere('name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate($this->perPage === 'all' ? SessionTime::count() : $this->perPage);

        return view('livewire.admin.master.session-time.admin-master-session-time-index', [
            'sessionTimes' => $sessionTimes,
        ]);
    }
}
