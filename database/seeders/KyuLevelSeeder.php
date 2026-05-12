<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KyuLevel;
use Illuminate\Support\Facades\DB;

class KyuLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('kyu_levels')->truncate();

        $levels = [
            'Kyu 5', 'Kyu 3', 'Kyu 2', 'Kyu 1',
            'Dan 1', 'Dan 2'
        ];

        foreach ($levels as $index => $level) {
            KyuLevel::create([
                'name' => $level,
                'color' => 'Standard', // Default color for now
                'order' => $index + 1
            ]);
        }
    }
}
