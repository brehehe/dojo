<?php

namespace Database\Seeders;

use App\Models\Referee;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RefereeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $password = Hash::make('password123');

        for ($i = 0; $i < 100; $i++) {
            $genderLabel = $faker->randomElement(['Laki-laki', 'Perempuan']);
            $gender = $genderLabel === 'Laki-laki' ? 'L' : 'P';

            $user = User::create([
                'name' => $faker->name($genderLabel === 'Laki-laki' ? 'male' : 'female'),
                'email' => $faker->unique()->userName().rand(1, 999).'@wasit.kempo.com',
                'password' => $password,
                'email_verified_at' => now(),
            ]);

            // Assign role 'Wasit' - assuming role exists or using spatie roles
            // In the codebase it seems they use Spatie format `$user->assignRole('Wasit')`
            if (class_exists(Role::class)) {
                $role = Role::firstOrCreate(['name' => 'Wasit']);
                $user->assignRole($role);
            }

            Referee::create([
                'user_id' => $user->id,
                'nik' => $faker->nik(),
                'phone' => $faker->phoneNumber(),
                'gender' => $gender,
                'birth_place' => $faker->city(),
                'birth_date' => $faker->dateTimeBetween('-50 years', '-25 years')->format('Y-m-d'),
                'address' => $faker->address(),
                'certification_level' => $faker->randomElement(['Nasional', 'Daerah', 'Cabang', 'Magang']),
                'license_number' => 'WST-'.rand(1000, 9999).'-'.date('Y'),
                'province' => $faker->state(),
                'city' => $faker->city(),
                // 'photo' left empty
            ]);
        }
    }
}
