<?php

namespace Database\Seeders;

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
            MatchNumberSeeder::class,
            CategorySeeder::class,
            PostSeeder::class,
            GallerySeeder::class,
            RoleSeeder::class,
            ContingentSeeder::class,
            CourtAndPoolSeeder::class,
            RefereeSeeder::class,
        ]);
    }
}
