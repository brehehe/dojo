<?php

namespace App\Livewire\Admin;

use App\Models\Court\Court;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

                $updateData = ['name' => $this->name];
                if (! $model->order) {
                    $updateData['order'] = (Court::max('order') ?? 0) + 1;
                }
                $model->update($updateData);

                $user = User::updateOrCreate([
                    'email' => 'court'.$model->id.'@gmail.com',
                ], [
                    'name' => 'Petugas '.$model->name,
                    'court_id' => $model->id,
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                ]);

                $user->assignRole('Court');

                $this->syncTabletUsers($model);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Data Lapangan (Court) telah diperbarui.',
                    'icon' => 'success',
                ]);
            } else {
                $court = Court::create([
                    'name' => $this->name,
                    'order' => (Court::max('order') ?? 0) + 1,
                ]);

                $user = User::create([
                    'name' => 'Petugas '.$court->name,
                    'email' => 'court'.$court->id.'@gmail.com',
                    'password' => bcrypt('password'),
                    'court_id' => $court->id,
                    'email_verified_at' => now(),
                ]);

                // Berikan role 'Court'
                $user->assignRole('Court');

                $this->syncTabletUsers($court);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Lapangan (Court) baru telah ditambahkan.',
                    'icon' => 'success',
                ]);
            }
        });

        $this->showingCourtModal = false;
    }

    private function syncTabletUsers(Court $court): void
    {
        // Index 1: Wasit Utama
        $this->createOrUpdateTabletUser($court, 1, 'wasitutama');

        // Index 2-5: Wasit 2, 3, 4, 5
        for ($i = 2; $i <= 5; $i++) {
            $this->createOrUpdateTabletUser($court, $i, 'wasit'.$i);
        }
    }

    private function createOrUpdateTabletUser(Court $court, int $index, string $suffix): void
    {
        $email = 'tabletcourt'.$court->order.$suffix.'@gmail.com';

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Tablet '.$court->name.' - '.ucwords(str_replace('wasit', 'Wasit ', $suffix)),
                'password' => bcrypt(Str::random(12)),
                'court_id' => $court->id,
                'judge_index' => $index,
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole('Wasit');
    }

    public function deleteCourt($id)
    {
        $model = Court::findOrFail($id);
        DB::transaction(function () use ($model) {
            User::where('court_id', $model->id)->delete();
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
