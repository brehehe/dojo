<?php

namespace Database\Seeders;

use App\Models\Court\Court;
use App\Models\Rundown\Rundown;
use App\Models\SessionTime;
use Illuminate\Database\Seeder;

class SessionRundownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Master Rundown (Pertandingan)
        $rundowns = [
            [
                'name' => 'Babak Penyisihan - Hari 1',
                'type' => 'pertandingan',
                'description' => 'Seluruh nomor pertandingan penyisihan hari pertama',
                'date' => now(),
                'order' => 1,
            ],
            [
                'name' => 'Babak Penyisihan - Hari 2',
                'type' => 'pertandingan',
                'description' => 'Lanjutan penyisihan dan penentuan finalis',
                'date' => now()->addDay(),
                'order' => 2,
            ],
            [
                'name' => 'Babak Final & Perebutan Juara',
                'type' => 'pertandingan',
                'description' => 'Pertandingan perebutan medali emas, perak, dan perunggu',
                'date' => now()->addDays(2),
                'order' => 3,
            ],
            [
                'name' => 'Upacara Pembukaan',
                'type' => 'seremonial',
                'description' => 'Opening ceremony dan parade kontingen',
                'date' => now(),
                'order' => 0,
            ],
        ];

        foreach ($rundowns as $r) {
            Rundown::updateOrCreate(
                ['name' => $r['name']],
                $r
            );
        }

        // 2. Seed Master Session Time
        $sessions = [
            [
                'name' => 'Sesi Pagi',
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
            ],
            [
                'name' => 'Sesi Siang',
                'start_time' => '13:00:00',
                'end_time' => '17:00:00',
            ],
            [
                'name' => 'Sesi Malam',
                'start_time' => '19:00:00',
                'end_time' => '22:00:00',
            ],
        ];

        foreach ($sessions as $s) {
            SessionTime::updateOrCreate(
                ['name' => $s['name']],
                $s
            );
        }

        for ($i = 1; $i <= 5; $i++) {
            Court::create([
                'name' => 'Court ' . $i,
                'order' => $i,
            ]);
        }
    }
}
