<?php

namespace App\Livewire\Admin;

use App\Models\Referee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class NewRefereeIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    // User Fields
    public $name;

    public $email;

    public $password;

    // Referee Fields
    public $nik;

    public $phone;

    public $gender;

    public $birth_place;

    public $birth_date;

    public $address;

    public $certification_level;

    public $license_number;

    public $province;

    public $city;

    public $showingRefereeModal = false;

    public $refereeIdBeingEdited = null;

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
        $this->reset(['name', 'email', 'password', 'nik', 'phone', 'gender', 'birth_place', 'birth_date', 'address', 'certification_level', 'license_number', 'province', 'city', 'refereeIdBeingEdited']);
        $this->showingRefereeModal = true;
    }

    public function showEditModal($id)
    {
        $this->resetValidation();
        $this->refereeIdBeingEdited = $id;
        $referee = Referee::with('user')->findOrFail($id);

        // Load User Data
        $this->name = $referee->user->name;
        $this->email = $referee->user->email;
        $this->password = '';

        // Load Referee Data
        $this->nik = $referee->nik;
        $this->phone = $referee->phone;
        $this->gender = $referee->gender;
        $this->birth_place = $referee->birth_place;
        $this->birth_date = $referee->birth_date ? Carbon::parse($referee->birth_date)->format('Y-m-d') : null;
        $this->address = $referee->address;
        $this->certification_level = $referee->certification_level;
        $this->license_number = $referee->license_number;
        $this->province = $referee->province;
        $this->city = $referee->city;

        $this->showingRefereeModal = true;
    }

    public function saveReferee()
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.($this->refereeIdBeingEdited ? Referee::find($this->refereeIdBeingEdited)->user_id : 'NULL'),
            'password' => $this->refereeIdBeingEdited ? 'nullable|min:8' : 'required|min:8',
            'phone' => 'nullable|numeric',
            'certification_level' => 'nullable|string',
        ];

        $this->validate($rules);

        DB::transaction(function () {
            if ($this->refereeIdBeingEdited) {
                $referee = Referee::findOrFail($this->refereeIdBeingEdited);
                $user = $referee->user;

                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                ]);

                if ($this->password) {
                    $user->update(['password' => Hash::make($this->password)]);
                }

                $referee->update([
                    'nik' => $this->nik,
                    'phone' => $this->phone,
                    'gender' => $this->gender,
                    'birth_place' => $this->birth_place,
                    'birth_date' => $this->birth_date,
                    'address' => $this->address,
                    'certification_level' => $this->certification_level,
                    'license_number' => $this->license_number,
                    'province' => $this->province,
                    'city' => $this->city,
                ]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Data wasit telah diperbarui.',
                    'icon' => 'success',
                ]);
            } else {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                ]);

                // Auto-assign Perwasitan role
                $user->assignRole('Perwasitan');

                Referee::create([
                    'user_id' => $user->id,
                    'nik' => $this->nik,
                    'phone' => $this->phone,
                    'gender' => $this->gender,
                    'birth_place' => $this->birth_place,
                    'birth_date' => $this->birth_date,
                    'address' => $this->address,
                    'certification_level' => $this->certification_level,
                    'license_number' => $this->license_number,
                    'province' => $this->province,
                    'city' => $this->city,
                ]);

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Wasit baru telah ditambahkan.',
                    'icon' => 'success',
                ]);
            }
        });

        $this->showingRefereeModal = false;
    }

    public function deleteReferee($id)
    {
        $referee = Referee::findOrFail($id);
        $user = $referee->user;

        if ($user->id === auth()->id()) {
            $this->dispatch('swal', [
                'title' => 'Ups!',
                'text' => 'Anda tidak bisa menghapus akun Anda sendiri.',
                'icon' => 'error',
            ]);

            return;
        }

        DB::transaction(function () use ($referee, $user) {
            $referee->delete();
            $user->delete();
        });

        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Wasit dan akun login terkait telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $query = Referee::with('user');

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            })
                ->orWhere('certification_level', 'like', '%'.$this->search.'%')
                ->orWhere('phone', 'like', '%'.$this->search.'%')
                ->orWhere('license_number', 'like', '%'.$this->search.'%');
        }

        $referees = $query->latest()->paginate($this->perPage === 'all' ? Referee::count() : $this->perPage);

        return view('livewire.admin.new-referee-index', [
            'referees' => $referees,
        ])->layout('layouts.premium', ['title' => 'Master Wasit (Referee)']);
    }
}
