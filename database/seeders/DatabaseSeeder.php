<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\PaymentMethod\PaymentMethod;
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
            CourtAndPoolSeeder::class,
            RefereeSeeder::class,
            MatchNumberSeeder::class,
            SessionRundownSeeder::class,
            TechniqueSeeder::class,
            TournamentDummySeeder::class,
            // ContingentSeeder::class,
            // Embu9ContingentSeeder::class,
        ]);

        PaymentMethod::create([
            'bank' => 'Tunai',
            'name' => 'Tunai',
        ]);

        PaymentMethod::create([
            'bank' => 'BNI',
            'account_number' => '0705667627',
            'name' => 'KONI Kabupaten Bekasi',
        ]);

        PaymentMethod::create([
            'bank' => 'BRI',
            'account_number' => '0705667627',
            'name' => 'KONI Kabupaten Bekasi',
        ]);

        PaymentMethod::create([
            'bank' => 'Mandiri',
            'account_number' => '0705667627',
            'name' => 'KONI Kabupaten Bekasi',
        ]);
    }
}
