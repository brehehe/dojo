<?php

namespace App\Livewire\Admin\Master\Court;

use App\Models\Court\Court;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class AdminMasterCourtIndex extends Component
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

    public function showEditModal($courtId)
    {
        $this->resetValidation();
        $this->courtIdBeingEdited = $courtId;
        $court = Court::findOrFail($courtId);

        // Load User Data
        $this->name = $court->name;
        $this->showingCourtModal = true;
    }

    public function saveCourt()
    {
        $rules = [
            'name' => 'required|max:255',
        ];

        $this->validate($rules);

        DB::transaction(function () {
            if ($this->courtIdBeingEdited) {
                $court = Court::findOrFail($this->courtIdBeingEdited);

                $court->update([
                    'name' => $this->name,
                ]);

                $this->syncTabletUsers($court);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Data Court telah diperbarui.', icon: 'success');
            } else {
                $court = Court::create([
                    'name' => $this->name,
                    'order' => (Court::max('order') ?? 0) + 1,
                ]);

                $this->syncTabletUsers($court);

                $this->dispatch('swal', title: 'Berhasil!', text: 'Court baru telah ditambahkan.', icon: 'success');
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

    public function deleteCourt($courtId)
    {
        $court = Court::findOrFail($courtId);
        DB::transaction(function () use ($court) {
            User::where('court_id', $court->id)->delete();
            $court->delete();
        });

        $this->dispatch('swal', title: 'Dihapus!', text: 'Court telah dihapus.', icon: 'success');
    }

    public function render()
    {
        $courts = Court::orWhere('name', 'ilike', '%'.$this->search.'%')
            ->latest()
            ->paginate($this->perPage === 'all' ? Court::count() : $this->perPage);

        return view('livewire.admin.master.court.admin-master-court-index', [
            'courts' => $courts,
        ]);
    }
}
