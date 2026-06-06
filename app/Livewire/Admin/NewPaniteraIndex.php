<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class NewPaniteraIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    public $name;

    public $email;

    public $password;

    public $userIdBeingEdited = null;

    public $showingModal = false;

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
        $this->reset(['name', 'email', 'password', 'userIdBeingEdited']);
        $this->showingModal = true;
    }

    public function showEditModal($id)
    {
        $this->resetValidation();
        $this->userIdBeingEdited = $id;
        $user = User::findOrFail($id);

        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->showingModal = true;
    }

    public function saveUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->userIdBeingEdited)],
        ];

        if (! $this->userIdBeingEdited) {
            $rules['password'] = 'required|min:8';
        } else {
            $rules['password'] = 'nullable|min:8';
        }

        $this->validate($rules);

        DB::transaction(function () {
            if ($this->userIdBeingEdited) {
                $user = User::findOrFail($this->userIdBeingEdited);
                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                ]);

                if ($this->password) {
                    $user->update(['password' => Hash::make($this->password)]);
                }

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Data Panitera telah diperbarui.',
                    'icon' => 'success',
                ]);
            } else {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => Hash::make($this->password),
                ]);

                $user->assignRole('Panitera');

                $this->dispatch('swal', [
                    'title' => 'Berhasil!',
                    'text' => 'Panitera baru telah ditambahkan.',
                    'icon' => 'success',
                ]);
            }
        });

        $this->showingModal = false;
        $this->reset(['name', 'email', 'password', 'userIdBeingEdited']);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Anda tidak bisa menghapus akun Anda sendiri.',
                'icon' => 'error',
            ]);

            return;
        }

        DB::transaction(function () use ($user) {
            $user->delete();
        });

        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => 'Panitera telah dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $operator = DB::connection()->getDriverName() === 'sqlite' ? 'like' : 'ilike';

        $query = User::role('Panitera');

        if ($this->search) {
            $query->where(function ($q) use ($operator) {
                $q->where('name', $operator, '%'.$this->search.'%')
                    ->orWhere('email', $operator, '%'.$this->search.'%');
            });
        }

        $users = $query->latest()->paginate($this->perPage === 'all' ? User::role('Panitera')->count() : $this->perPage);

        return view('livewire.admin.new-panitera-index', [
            'users' => $users,
        ])->layout('layouts.premium', ['title' => 'Data Panitera']);
    }
}
