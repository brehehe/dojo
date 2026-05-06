<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class NewRoleIndex extends Component
{
    use WithPagination;

    public $search = '';

    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'perPage' => ['except' => 10],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);

        if ($role->name === 'Super Admin') {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Role Super Admin tidak dapat dihapus.',
                'icon' => 'error',
            ]);

            return;
        }

        if ($role->users()->count() > 0) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Role ini masih memiliki user yang terhubung.',
                'icon' => 'error',
            ]);

            return;
        }

        $role->delete();
        $this->dispatch('swal', [
            'title' => 'Berhasil!',
            'text' => 'Role berhasil dihapus.',
            'icon' => 'success',
        ]);
    }

    public function render()
    {
        $roles = Role::withCount('users')
            ->where('name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate($this->perPage === 'all' ? Role::count() : $this->perPage);

        return view('livewire.admin.new-role-index', [
            'roles' => $roles,
        ])->layout('layouts.premium', ['title' => 'Role & Hak Akses']);
    }
}
