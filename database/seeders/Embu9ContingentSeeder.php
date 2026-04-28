<?php

namespace Database\Seeders;

use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Group\WeightGroup;
use App\Models\MatchNumber\MatchNumber;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Embu9ContingentSeeder extends Seeder
{
    public function run(): void
    {
        // Find three Tandoku Embu matches
        $matches = MatchNumber::where('draft_type', 'embu')
            ->where('max_athletes', 1) // Tandoku
            ->take(3)
            ->get();

        if ($matches->count() < 3) {
            $this->command->error('Not enough Embu Tandoku matches found. Run MatchNumberSeeder first.');

            return;
        }

        $counts = [9, 10, 20];
        $weightGroups = WeightGroup::orderBy('order')->get();

        foreach ($matches as $index => $match) {
            $targetCount = $counts[$index];
            $ageGroup = $match->ageGroup;
            $gender = $match->gender; // Male or Female

            $this->command->info("Seeding {$targetCount} contingents for Match: {$match->name}...");

            DB::transaction(function () use ($match, $ageGroup, $weightGroups, $gender, $targetCount) {
                for ($c = 1; $c <= $targetCount; $c++) {
                    $uniqueCode = "{$targetCount}-".str_pad($c, 2, '0', STR_PAD_LEFT);
                    $contingentName = "Kontingen Uji {$uniqueCode}";

                    // 1. Create User
                    $email = "embu_test_{$uniqueCode}@dummy.test";
                    $user = User::firstOrCreate(
                        ['email' => $email],
                        [
                            'name' => $contingentName,
                            'password' => Hash::make('password'),
                        ]
                    );
                    $user->assignRole('Contingent');

                    // 2. Create Contingent
                    $contingent = Contingent::firstOrCreate(
                        ['email' => $email],
                        [
                            'user_id' => $user->id,
                            'name' => $contingentName,
                            'kab_kota' => 'Kota '.$uniqueCode,
                            'leader_name' => fake()->name(),
                            'leader_phone' => '08'.fake()->numerify('##########'),
                            'address' => fake()->address(),
                        ]
                    );

                    // 3. Create Registration
                    $registration = Registration::firstOrCreate(
                        ['contingent_id' => $contingent->id],
                        [
                            'total_cost' => 2500000,
                            'final_amount' => 2500123,
                            'unique_code' => 123,
                            'payment_method' => 'BCA',
                            'referral_code' => 'TEST-'.Str::random(4),
                            'status' => 'verified',
                            'sim_perkemi_confirm' => 'Ya',
                        ]
                    );

                    // 4. Create Athlete
                    $athlete = Athlete::create([
                        'nik' => '999'.fake()->numerify('#############'),
                        'name' => fake()->name($gender === 'Male' ? 'male' : 'female'),
                        'birth_place' => fake()->city(),
                        'birth_date' => fake()->dateTimeBetween('-25 years', '-15 years')->format('Y-m-d'),
                        'gender' => $gender,
                        'blood_type' => 'O',
                        'bpjs_number' => fake()->numerify('##############'),
                        'bpjs_status' => 'Aktif',
                        'address' => fake('id_ID')->address(),
                        'phone' => '08'.fake()->numerify('##########'),
                    ]);

                    // 5. Attach athlete to registration globally
                    $wg = $weightGroups->isNotEmpty() ? $weightGroups->random() : null;
                    $registration->athletes()->attach($athlete->id, [
                        'weight' => rand(40, 80),
                        'kyu' => 'Kyu 1',
                        'age_group' => $ageGroup->name,
                        'match_type' => 'Embu',
                        'created_at' => now(),
                        'updated_at' => now(),
                        'weight_group_id' => $wg ? $wg->id : null,
                    ]);

                    // 6. Attach athlete to match number
                    $match->athletes()->attach($athlete->id, [
                        'registration_id' => $registration->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });

            $this->command->info("✅ Successfully seeded {$targetCount} contingents for {$match->name}");
        }
    }
}
