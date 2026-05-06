<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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

        // Clear existing roles and permissions for a fresh start
        Role::query()->delete();
        Permission::query()->delete();

        // Create Roles only
        $roles = [
            'Super Admin',
            'Admin',
            'Pendaftaran',
            'Pertandingan',
            'Panitera',
            'Perwasitan',
            'Arbitrase',
            'Contingent',
            'Koordinator Lapangan',
        ];

        foreach ($roles as $roleName) {
            $user = User::firstOrCreate(
                ['email' => Str::slug($roleName).'@smart-perkemi.id'],
                [
                    'name' => $roleName,
                    'password' => Hash::make('password'),
                ]
            );

            Role::firstOrCreate(['name' => $roleName]);

            $user->assignRole($roleName);

            // Generate additional dummy users for Panitera and Koordinator Lapangan
            if ($roleName === 'Panitera') {
                for ($i = 1; $i <= 100; $i++) {
                    $panitera = User::firstOrCreate(
                        ['email' => "panitera{$i}@smart-perkemi.id"],
                        [
                            'name' => "Panitera Dummy {$i}",
                            'password' => Hash::make('password'),
                        ]
                    );
                    $panitera->assignRole($roleName);
                }
            }

            if ($roleName === 'Koordinator Lapangan') {
                for ($i = 1; $i <= 50; $i++) {
                    $koor = User::firstOrCreate(
                        ['email' => "koordinator{$i}@smart-perkemi.id"],
                        [
                            'name' => "Koor Lapangan Dummy {$i}",
                            'password' => Hash::make('password'),
                        ]
                    );
                    $koor->assignRole($roleName);
                }
            }
        }

        // Re-create Initial Super Admin User if doesn't exist
        $user = User::where('email', 'admin@smart-perkemi.id')->first() ?: User::factory()->create([
            'name' => 'Admin Perkemi',
            'email' => 'admin@smart-perkemi.id',
            'password' => Hash::make('password'),
        ]);

        // Ensure user has super admin role (clear roles first)
        $user->roles()->detach();
        $user->assignRole('Super Admin');
    }
}
