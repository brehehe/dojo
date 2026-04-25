<?php

namespace Database\Seeders;

use App\Models\KyuLevel;
use App\Models\Technique\Technique;
use Illuminate\Database\Seeder;

class TechniqueSeeder extends Seeder
{
    /**
     * Seed all techniques per Kyu/Dan level based on the official PERKEMI excel.
     * KyuLevel IDs: Kyu6=1, Kyu5=2, Kyu4=3, Kyu3=4, Kyu2=5, Kyu1=6, Dan1=7, Dan2=8, Dan3=9, Dan4=10, Dan5=11
     */
    public function run(): void
    {
        // Clear existing techniques
        Technique::truncate();

        $data = [
            'Kyu 5' => [
                'Ryusui geri (mae)',
                'Uwa uke geri (omote, ura)',
                'Shita uke geri',
                'Shita uke jun geri',
            ],
            'Kyu 4' => [
                'Uchi age zuki (ura, omote)',
                'Uchi age geri (ura, omote)',
                'Soto uke zuki (ura, omote)',
                'Soto uke geri (ura, omote)',
            ],
            'Kyu 3' => [
                'Uchi uke geri (ura, omote)',
                'Soto oshi uke zuki',
                'Juji uke geri',
                'Tsuki ten ichi',
                'Gyaku gote – mae yubi gatame',
            ],
            'Kyu 2' => [
                'Soto uke zuki ren han ko',
                'Kiri nuki (uchi)',
                'Shita uke geri',
                'Kiri kaeshi nuki (morote)',
                'Shita uke jun geri',
                'Kote nuki ren han ko',
                'Uchi age geri ren han ko',
                'Ryote yori nuki',
                'Ryusui geri (mae)',
                'Okuri gote (katate) – okuri gatame',
            ],
            'Kyu 1' => [
                'Chidori gaeshi ren han ko',
                'Johaku nuki (katate)',
                'Soto uke geri ren han ko',
                'Hiki nuki (morote)',
                'Uwa uke geri (ura)',
                'Sode nuki',
                'Uchi age zuki ren han ko',
                'Tsuki nuki (morote)',
                'Kusshin zuki geri',
                'Nidan nuki',
            ],
            'Dan 1' => [
                'Tsubame gaeshi ren han ko',
                'Juji nuki (ryote)',
                'Gyaku tenshin geri ren han ko',
                'Johaku nuki (ryote)',
                'Soto oshi uke zuki ren han ko',
                'Maki nuki (morote)',
                'Uchi oshi uke geri ren han ko',
                'Wa nuki (morote)',
                'Harai uke geri ren han ko',
                'Okuri gote (ryote) – okuri gatame',
            ],
            'Dan 2' => [
                'Tai ten ichi ke keri ten san',
                'Jun geri chi san ke tsuki ten ni',
                'Ryu nage – ryu gatame',
                'Morote gyaku gote (Penyerang: ippon se nage)',
                'Shita uke geri kote nage – tembin gatame (ura)',
                'Uwa uke nage – kannuki gatame',
            ],
            'Dan 3' => [
                'Kusshin geri tenkai ren geri',
                'Mikazuki gaeshi kari ashi',
                'Morote kiri kaeshi nage (Penyerang: Ude ushiro neji age)',
                'Ryote katate nage – kannuki gatame',
                'Keri ten ichi sukui nage',
                'Sode maki gaeshi – kannuki gatame (Penyerang: Pegang lengan baju & ashi barai)',
            ],
            'Dan 4' => [
                'Gedan gaeshi ke tobi ren geri',
                'Chudan gaeshi ke uchi uke zuki',
                'Kubi jime shuho juji nage',
                'Maki komi gote',
                'Oshi uke maki nage',
                'Hangetsu gaeshi sukui kubi nage ke fukko chi ni',
            ],
            'Dan 5' => [
                'Katate nage melawan sashi komi mawashi geri & jo chu ni ren zuki',
                'Keri ten san ke tora daoshi',
                'Katate nage kiri gaeshi',
                'Furisute omote nage',
                'Uwa uke tembin nage',
                'Kaishin zuki ke osae kannuku nage soto',
            ],
        ];

        $kyuLevels = KyuLevel::all()->keyBy('name');

        $order = 1;
        foreach ($data as $levelName => $techniques) {
            $kyuLevel = $kyuLevels->get($levelName);

            foreach ($techniques as $index => $techniqueName) {
                Technique::create([
                    'name' => $techniqueName,
                    'kyu_level_id' => $kyuLevel?->id,
                    'order' => $order++,
                ]);
            }
        }
    }
}
