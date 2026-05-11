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
                'name' => 'Upacara Pembukaan',
                'type' => 'seremonial',
                'description' => 'Opening ceremony dan parade kontingen',
                'date' => '2026-06-14', // 14 Juni
                'order' => 1,
            ],
            [
                'name' => 'Babak Penyisihan - Hari 1',
                'type' => 'pertandingan',
                'description' => 'Seluruh nomor pertandingan penyisihan hari pertama',
                'date' => '2026-06-15', // 15 Juni
                'order' => 2,
            ],
            [
                'name' => 'Babak Final & Perebutan Juara',
                'type' => 'pertandingan',
                'description' => 'Pertandingan perebutan medali emas, perak, dan perunggu',
                'date' => '2026-06-16',
                'order' => 3,
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
                'start_time' => '07:30:00',
                'end_time' => '12:00:00',
            ],
            [
                'name' => 'Sesi Sore',
                'start_time' => '13:00:00',
                'end_time' => '17:30:00',
            ],
        ];

        foreach ($sessions as $s) {
            SessionTime::updateOrCreate(
                ['name' => $s['name']],
                $s
            );
        }

        for ($i = 1; $i <= 2; $i++) {
            Court::create([
                'name' => 'Court '.$i,
                'order' => $i,
            ]);
        }
    }
}
