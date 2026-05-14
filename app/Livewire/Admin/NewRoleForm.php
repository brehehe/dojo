<?php

namespace App\Livewire\Admin;

use Illuminate\Validation\Rule;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class NewRoleForm extends Component
{
    public $roleId;

    public $name;

    public $selectedPermissions = [];

    public $isEdit = false;

    public function mount($id = null)
    {
        if ($id) {
            $this->isEdit = true;
            $this->roleId = $id;
            $role = Role::findOrFail($id);
            $this->name = $role->name;
            $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
        }
    }

    public function save()
    {
       $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->ignore($this->roleId),
            ],
        ]);

        if ($this->isEdit) {
            $role = Role::findOrFail($this->roleId);
            $role->update(['name' => $this->name]);
            $role->syncPermissions($this->selectedPermissions);
            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Role berhasil diperbarui.',
                'icon' => 'success',
            ]);
        } else {
            $role = Role::create(['name' => $this->name]);
            $role->syncPermissions($this->selectedPermissions);
            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Role baru ditambahkan.',
                'icon' => 'success',
            ]);
        }

        return $this->redirect(route('admin.new-roles'), navigate: true);
    }

    public function render()
    {
        $permissions = Permission::all()->groupBy(function ($item) {
            $name = $item->name;
            if (str_contains($name, '.')) {
                return explode('.', $name)[0];
            }

            return 'General';
        });

        return view('livewire.admin.new-role-form', [
            'permissionGroups' => $permissions,
        ])->layout('layouts.premium', ['title' => $this->isEdit ? 'Edit Role' : 'Tambah Role']);
    }
}
