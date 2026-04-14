<?php

namespace Database\Seeders;

use App\Models\Contingent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ContingentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // For Postgres FK constraint checks
        DB::statement('SET session_replication_role = \'replica\';');
        Contingent::truncate();

        // Remove existing Sura/Baya users if they exist to prevent duplication on re-run
        User::whereIn('email', ['sura@example.com', 'baya@example.com'])->delete();
        DB::statement('SET session_replication_role = \'origin\';');

        $contingents = [
            [
                'name' => 'Sura',
                'email' => 'sura@example.com',
                'password' => 'password',
                'contingent_name' => 'Kontingen Sura',
                'contingent_city' => 'Surabaya',
                'leader_name' => 'Ketua Sura',
                'leader_phone' => '081234567890',
                'address' => 'Jl. Sura No. 1',
            ],
            [
                'name' => 'Baya',
                'email' => 'baya@example.com',
                'password' => 'password',
                'contingent_name' => 'Kontingen Baya',
                'contingent_city' => 'Surabaya',
                'leader_name' => 'Ketua Baya',
                'leader_phone' => '081234567891',
                'address' => 'Jl. Baya No. 2',
            ],
        ];

        foreach ($contingents as $data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Assign 'Contingent' role specifically for member registration
            $user->assignRole('Contingent');

            Contingent::create([
                'user_id' => $user->id,
                'name' => $data['contingent_name'],
                'kab_kota' => $data['contingent_city'],
                'leader_name' => $data['leader_name'],
                'leader_phone' => $data['leader_phone'],
                'email' => $user->email,
                'address' => $data['address'],
            ]);
        }
    }
}
