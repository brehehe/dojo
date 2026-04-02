<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        Permission::create(['name' => 'manage master data']);
        Permission::create(['name' => 'view registrations']);
        Permission::create(['name' => 'confirm payments']);
        Permission::create(['name' => 'reject registrations']);

        // Create Roles and Assign Permissions
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $adminPendaftaran = Role::create(['name' => 'Admin Pendaftaran']);
        $adminPendaftaran->givePermissionTo([
            'view registrations',
            'confirm payments',
            'reject registrations',
        ]);

        // Create Initial Super Admin User
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@kempo.id',
            'password' => Hash::make('password'),
        ]);
        $user->assignRole($superAdmin);

        // Create Initial Admin Pendaftaran User
        $adminReg = User::create([
            'name' => 'Admin Pendaftaran',
            'email' => 'pendaftaran@kempo.id',
            'password' => Hash::make('password'),
        ]);
        $adminReg->assignRole($adminPendaftaran);
    }
}
