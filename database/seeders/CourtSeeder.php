<?php

namespace Database\Seeders;

use App\Models\Court\Court;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 3; $i++) {
            $court = Court::create([
                'name' => 'Court '.$i,
                'order' => $i,
            ]);

            // Buat User khusus untuk Court ini
            $user = User::create([
                'name' => 'Petugas '.$court->name,
                'email' => 'court'.$i.'@gmail.com',
                'password' => bcrypt(Str::random(12)),
                'court_id' => $court->id,
                'email_verified_at' => now(),
            ]);

            $user->assignRole('Court');
        }
    }
}
