<?php

namespace App\Console\Commands;

use App\Models\Athlete;
use App\Models\Contingent;
use App\Models\Registration;
use App\Models\MatchNumber\MatchNumber;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

#[Signature('app:seed-dummy-contingents {--reset : Reset dummy data instead of seeding}')]
#[Description('Seed dummy data for contingents (except Surabaya A) or reset them')]
class SeedDummyContingents extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $reset = $this->option('reset');

        // Find Surabaya A contingent
        $surabayaA = Contingent::where('name', 'Surabaya A')->first();
        
        if (!$surabayaA) {
            $this->error('Contingent Surabaya A not found. Please run DummySeeder first.');
            return 1;
        }

        if ($reset) {
            $this->info('Resetting dummy data (keeping Surabaya A)...');
            $this->resetData($surabayaA->id);
            $this->info('✅ Reset completed.');
            return 0;
        }

        $this->info('Seeding dummy data for other contingents...');
        $this->seedData($surabayaA->id);
        $this->info('✅ Seeding completed.');
        return 0;
    }

    protected function resetData($surabayaAId)
    {
        $registrations = Registration::where('contingent_id', '!=', $surabayaAId)->get();
        $count = 0;

        foreach ($registrations as $reg) {
            // Delete pivot records for match numbers
            DB::table('athlete_match_number')->where('registration_id', $reg->id)->delete();

            // Get athletes to check if they should be deleted
            $athletes = $reg->athletes;

            // Detach athletes from registration
            $reg->athletes()->detach();

            // Delete athletes if they don't belong to Surabaya A
            foreach ($athletes as $athlete) {
                $belongsToSurabayaA = $athlete->contingents()
                    ->where('contingent_id', $surabayaAId)
                    ->exists();

                if (!$belongsToSurabayaA) {
                    $athlete->contingents()->detach();
                    $athlete->delete();
                }
            }

            // Detach officials
            if (method_exists($reg, 'officials')) {
                $reg->officials()->detach();
            }

            // Delete registration
            $reg->delete();
            $count++;
        }

        $this->info("Deleted {$count} registrations and associated dummy data.");
    }

    protected function seedData($surabayaAId)
    {
        $contingents = Contingent::where('id', '!=', $surabayaAId)->get();
        $matchNumbers = MatchNumber::all();
        
        if ($matchNumbers->isEmpty()) {
            $this->error('No MatchNumbers found. Please run DummySeeder first.');
            return;
        }

        if ($contingents->count() < 4) {
            $this->error('Not enough contingents to seed 4 per match number.');
            return;
        }

        $count = 0;
        foreach ($matchNumbers as $matchNumber) {
            $this->info("Seeding for match number: {$matchNumber->name}");

            // Pick between 4 and 10 random contingents for this match number
            $numContingents = rand(4, min(10, $contingents->count()));
            $pickedContingents = $contingents->random($numContingents);

            foreach ($pickedContingents as $contingent) {
                // Find or create registration for this contingent
                $registration = Registration::firstOrCreate(
                    ['contingent_id' => $contingent->id],
                    [
                        'total_cost' => 1000000,
                        'final_amount' => 1000000,
                        'unique_code' => rand(100, 999),
                        'payment_method' => 'Transfer',
                        'referral_code' => 'DUMMY-' . strtoupper(Str::random(5)),
                        'status' => 'pending',
                        'sim_perkemi_confirm' => true,
                    ]
                );

                // Determine how many athletes to create based on max_athletes
                $numAthletes = $matchNumber->max_athletes > 0 ? $matchNumber->max_athletes : 1;

                for ($i = 0; $i < $numAthletes; $i++) {
                    $athlete = Athlete::create([
                        'name' => fake()->name,
                        'nik' => fake()->numerify('################'),
                        'nik_kenshi' => fake()->numerify('##########'),
                        'gender' => $matchNumber->gender === 'Mix' ? fake()->randomElement(['Male', 'Female']) : $matchNumber->gender,
                        'birth_place' => fake()->city,
                        'blood_type' => fake()->randomElement(['A', 'B', 'AB', 'O']),
                        'birth_date' => fake()->date('Y-m-d', '2005-01-01'),
                        'address' => fake()->address,
                        'dojo_origin' => $contingent->name,
                        'phone' => fake()->phoneNumber,
                        'bpjs_number' => fake()->numerify('#############'),
                        'bpjs_status' => 'Aktif',
                    ]);

                    // Link to contingent
                    $athlete->contingents()->attach($contingent->id, [
                        'is_primary' => true,
                        'joined_at' => now(),
                    ]);

                    // Link to registration
                    $registration->athletes()->attach($athlete->id, [
                        'weight' => rand(40, 80),
                        'weight_group_id' => null,
                        'kyu' => 'Kyu 1',
                        'age_group' => $matchNumber->ageGroup?->name ?? 'Dewasa',
                        'rank' => 'Kyu 1',
                        'dojo_origin' => $contingent->name,
                        'city' => $contingent->name,
                        'match_type' => $matchNumber->draft_type === 'embu' ? 'Embu' : 'Randori',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Link to match number
                    $athlete->matchNumbers()->attach($matchNumber->id, [
                        'registration_id' => $registration->id,
                        'technique_ids' => json_encode([]),
                    ]);
                }
            }
            $count++;
        }

        $this->info("Seeded 4-10 contingents for each of the {$count} match numbers.");
    }
}
