<?php

namespace Database\Seeders;

use App\Models\Contingent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ContingentSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clear existing contingent and their users to start fresh
        DB::statement('SET session_replication_role = \'replica\';');

        // Optionally truncate related tables if you want a clean slate for contingents
        // But the user only asked to seed contingents.
        // We delete users with role 'Contingent' and truncate Contingent table.
        Contingent::truncate();
        User::role('Contingent')->delete();

        DB::statement('SET session_replication_role = \'origin\';');

        $this->command->info('Seeding contingent master data from image list...');

        // 2. Define specific contingents from image list
        $contingentsToSeed = [
            ['name' => 'Surabaya A', 'username' => 'surabayaa'],
            ['name' => 'Surabaya B', 'username' => 'surabayab'],
            ['name' => 'Surabaya C', 'username' => 'surabayac'],
            ['name' => 'Surabaya D', 'username' => 'surabayad'],
            ['name' => 'Bangkalan A', 'username' => 'bangkalana'],
            ['name' => 'Bangkalan B', 'username' => 'bangkalanb'],
            ['name' => 'Kota Malang 1', 'username' => 'malang1'],
            ['name' => 'Kota Malang 3', 'username' => 'malang3'],
            ['name' => 'Kota Kediri', 'username' => 'kediri'],
            ['name' => 'Jombang', 'username' => 'jombang'],
            ['name' => 'Banyuwangi', 'username' => 'banyuwangi'],
            ['name' => 'Sidoarjo', 'username' => 'sidoarjo'],
            ['name' => 'Jember', 'username' => 'jember'],
            ['name' => 'Gresik', 'username' => 'gresik'],
            ['name' => 'Pasuruan', 'username' => 'pasuruan'],
        ];

        foreach ($contingentsToSeed as $cData) {
            $cName = $cData['name'];
            $email = $cData['username'].'@smart-perkemi.id';

            // Create User
            $user = User::create([
                'name' => $cName,
                'email' => $email,
                'password' => Hash::make(Str::random(12)),
            ]);

            $user->assignRole('Contingent');

            // Create Contingent
            Contingent::create([
                'user_id' => $user->id,
                'name' => $cName,
                'kab_kota' => $cName,
                'leader_name' => 'Ketua '.$cName,
                'leader_phone' => null,
                'email' => null,
                'address' => null,
            ]);
        }

        $this->command->info('✅ Contingent master data seeded successfully!');
    }
}
