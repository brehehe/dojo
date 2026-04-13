<?php

namespace App\Livewire\Admin\Master\Role;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.admin')]
class AdminMasterRoleIndex extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'Super Admin') {
            $this->dispatch('swal', title: 'Gagal!', text: 'Role Super Admin tidak dapat dihapus.', icon: 'error');

            return;
        }

        if ($role->users()->count() > 0) {
            $this->dispatch('swal', title: 'Gagal!', text: 'Role ini masih memiliki user yang terhubung.', icon: 'error');

            return;
        }

        $role->delete();
        $this->dispatch('swal', title: 'Berhasil!', text: 'Role berhasil dihapus.', icon: 'success');
    }

    public function render()
    {
        $roles = Role::withCount('users')
            ->where('name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.admin.master.role.admin-master-role-index', [
            'roles' => $roles,
        ]);
    }
}
