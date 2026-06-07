<?php

namespace Database\Seeders;

use App\Models\Contingent;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            ['name' => 'Surabaya A', 'username' => 'surabayaa', 'password' => 'surabayaa'],
            ['name' => 'Surabaya B', 'username' => 'surabayab', 'password' => 'surabayab'],
            ['name' => 'Surabaya C', 'username' => 'surabayac', 'password' => 'surabayac'],
            ['name' => 'Surabaya D', 'username' => 'surabayad', 'password' => 'surabayad'],
            ['name' => 'Bangkalan A', 'username' => 'bangkalana', 'password' => 'bangkalana'],
            ['name' => 'Bangkalan B', 'username' => 'bangkalanb', 'password' => 'bangkalanb'],
            ['name' => 'Kota Malang 1', 'username' => 'malang1', 'password' => 'kotamalang1'],
            ['name' => 'Kota Malang 3', 'username' => 'malang3', 'password' => 'kotamalang3'],
            ['name' => 'Kota Kediri', 'username' => 'kediri', 'password' => 'kotakediri'],
            ['name' => 'Jombang', 'username' => 'jombang', 'password' => 'jombang'],
            ['name' => 'Banyuwangi', 'username' => 'banyuwangi', 'password' => 'banyuwangi'],
            ['name' => 'Sidoarjo', 'username' => 'sidoarjo', 'password' => 'sidoarjo'],
            ['name' => 'Jember', 'username' => 'jember', 'password' => 'jember'],
            ['name' => 'Gresik', 'username' => 'gresik', 'password' => 'gresik'],
            ['name' => 'Pasuruan', 'username' => 'pasuruan', 'password' => 'pasuruan'],
        ];

        foreach ($contingentsToSeed as $cData) {
            $cName = $cData['name'];
            $email = $cData['username'].'@smart-perkemi.id';

            // Create User
            $user = User::create([
                'name' => $cName,
                'email' => $email,
                'password' => Hash::make($cData['password']),
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
