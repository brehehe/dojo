<?php

namespace Database\Seeders;

use App\Models\KyuLevel;
use Illuminate\Database\Seeder;

class KyuLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['name' => 'Kyu 10', 'color' => 'Putih', 'order' => 10],
            ['name' => 'Kyu 9', 'color' => 'Kuning', 'order' => 9],
            ['name' => 'Kyu 8', 'color' => 'Orange', 'order' => 8],
            ['name' => 'Kyu 7', 'color' => 'Hijau', 'order' => 7],
            ['name' => 'Kyu 6', 'color' => 'Biru', 'order' => 6],
            ['name' => 'Kyu 5', 'color' => 'Biru Tua', 'order' => 5],
            ['name' => 'Kyu 4', 'color' => 'Ungu', 'order' => 4],
            ['name' => 'Kyu 3', 'color' => 'Coklat', 'order' => 3],
            ['name' => 'Kyu 2', 'color' => 'Coklat', 'order' => 2],
            ['name' => 'Kyu 1', 'color' => 'Coklat', 'order' => 1],
        ];

        foreach ($levels as $level) {
            KyuLevel::updateOrCreate(['name' => $level['name']], $level);
        }
    }
}
