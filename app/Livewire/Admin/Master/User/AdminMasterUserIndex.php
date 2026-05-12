<?php
namespace App\Livewire\Admin\Master\User;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

#[Layout('layouts.admin')]
class AdminMasterUserIndex extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'livewire.admin.pagination';
    }

    public $search = '';
    public $name, $email, $password, $selectedRole;
    public $perPage = 5;
    public $userIdBeingEdited;
    public $showingUserModal = false;

    protected $queryString = ['search', 'perPage'];

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function showCreateModal()
    {
        $this->reset(['name', 'email', 'password', 'selectedRole', 'userIdBeingEdited']);
        $this->showingUserModal = true;
    }

    public function showEditModal(User $user)
    {
        $this->userIdBeingEdited = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = '';
        $this->selectedRole = $user->roles->first()?->name;
        $this->showingUserModal = true;
    }

    public function saveUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->userIdBeingEdited)],
            'selectedRole' => 'required|exists:roles,name',
        ];

        if (!$this->userIdBeingEdited) {
            $rules['password'] = 'required|min:8';
        } else {
            $rules['password'] = 'nullable|min:8';
        }

        $this->validate($rules);

        if ($this->userIdBeingEdited) {
            $user = User::findOrFail($this->userIdBeingEdited);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
            ]);

            if ($this->password) {
                $user->update(['password' => Hash::make($this->password)]);
            }

            $user->syncRoles([$this->selectedRole]);

            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'User berhasil diperbarui.',
                'icon' => 'success',
            ]);
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);

            $user->assignRole($this->selectedRole);

            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'User baru berhasil ditambahkan.',
                'icon' => 'success',
            ]);
        }

        $this->showingUserModal = false;
        $this->reset(['name', 'email', 'password', 'selectedRole', 'userIdBeingEdited']);
    }

    public function deleteUser(User $user)
    {
        // Simple delete without explicit permission check as requested
        if ($user->id === auth()->id()) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Anda tidak bisa menghapus akun sendiri.',
                'icon' => 'error',
            ]);
            return;
        }

        $user->delete();

        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'User berhasil dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $users = User::with('roles')
            ->where(function ($query) {
                $query->where('name', 'ilike', '%' . $this->search . '%')
                    ->orWhere('email', 'ilike', '%' . $this->search . '%');
            })
            ->latest();

        if ($this->perPage === 'all') {
            $users = $users->paginate($users->count() ?: 10);
        } else {
            $users = $users->paginate($this->perPage);
        }

        $roles = Role::all();

        return view('livewire.admin.master.user.admin-master-user-index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }
}
