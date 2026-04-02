<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            KyuLevelSeeder::class,
            CategorySeeder::class,
            PostSeeder::class,
            GallerySeeder::class,
            RoleSeeder::class,
            ContingentSeeder::class,
        ]);

        $user = User::factory()->create([
            'name' => 'Admin Perkemi',
            'email' => 'admin@smart-perkemi.id',
        ]);

        $user->assignRole('Super Admin');
    }
}
