<?php

namespace Database\Seeders;

use App\Models\Court\Court;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TabRefeeCourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courts = Court::all();

        foreach ($courts as $court) {
            // Index 1: Wasit Utama
            $this->createTabletUser($court, 1, 'wasitutama');

            // Index 2-5: Wasit 2, 3, 4, 5
            for ($i = 2; $i <= 5; $i++) {
                $this->createTabletUser($court, $i, 'wasit'.$i);
            }
        }
    }

    private function createTabletUser($court, $index, $suffix)
    {
        $email = 'tabletcourt'.$court->order.$suffix.'@gmail.com';

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Tablet '.$court->name.' - '.ucwords(str_replace('wasit', 'Wasit ', $suffix)),
                'password' => bcrypt(Str::random(12)),
                'court_id' => $court->id,
                'judge_index' => $index,
                'email_verified_at' => now(),
            ]
        );

        $user->assignRole('Wasit');
    }
}
