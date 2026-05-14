<?php

namespace App\Livewire\Admin;

use App\Models\Court\Court;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class NewCourtIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    public $name;

    public $showingCourtModal = false;

    public $courtIdBeingEdited = null;

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
        $this->reset(['name', 'courtIdBeingEdited']);
        $this->showingCourtModal = true;
    }

    public function showEditModal($id)
    {
        $this->resetValidation();
        $this->courtIdBeingEdited = $id;
        $model = Court::findOrFail($id);

        $this->name = $model->name;
        $this->showingCourtModal = true;
    }

    public function saveCourt()
    {
        $this->validate([
            'name' => 'required|max:255',
        ]);

        DB::transaction(function () {
            if ($this->courtIdBeingEdited) {
                $model = Court::findOrFail($this->courtIdBeingEdited);
                $model->update(['name' => $this->name]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Data Lapangan (Court) telah diperbarui.',
                    'icon' => 'success',
                ]);
            } else {
                $court = Court::create(['name' => $this->name]);

                // Buat User otomatis untuk Lapangan baru ini
                $user = User::create([
                    'name' => 'Petugas ' . $court->name,
                    'email' => strtolower(str_replace(' ', '', $court->name)) . $court->id . '@gmail.com',
                    'password' => bcrypt('password'), // Password default
                    'court_id' => $court->id,
                    'email_verified_at' => now(),
                ]);

                // Berikan role 'Court'
                $user->assignRole('Court');

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Lapangan (Court) baru telah ditambahkan.',
                    'icon' => 'success',
                ]);
            }
        });

        $this->showingCourtModal = false;
    }

    public function deleteCourt($id)
    {
        $model = Court::findOrFail($id);
        DB::transaction(function () use ($model) {
            $model->delete();
        });

        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Lapangan (Court) telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $query = Court::query();

        if ($this->search) {
            $query->where('name', 'ilike', '%'.$this->search.'%');
        }

        $courts = $query->latest()->paginate($this->perPage === 'all' ? Court::count() : $this->perPage);

        return view('livewire.admin.new-court-index', [
            'courts' => $courts,
        ])->layout('layouts.premium', ['title' => 'Master Lapangan (Court)']);
    }
}
