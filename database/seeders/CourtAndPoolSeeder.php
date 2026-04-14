<?php

namespace Database\Seeders;

use App\Models\Court\Court;
use App\Models\Pool\Pool;
use App\Models\Technique\Technique;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourtAndPoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET session_replication_role = \'replica\';');
        Court::truncate();
        Pool::truncate();
        Technique::truncate();
        DB::statement('SET session_replication_role = \'origin\';');

        // Create 5 Courts
        for ($i = 1; $i <= 5; $i++) {
            Court::create([
                'name' => 'Court '.$i,
                'order' => $i,
            ]);
        }

        // Create 4 Pools
        for ($i = 1; $i <= 4; $i++) {
            Pool::create([
                'name' => 'Pool '.$i,
                'order' => $i,
            ]);
        }

        // Create 20 Pools
        for ($i = 1; $i <= 20; $i++) {
            Technique::create([
                'name' => 'Teknik '.$i,
                'order' => $i,
            ]);
        }

    }
}
