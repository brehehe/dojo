<?php

namespace Database\Seeders;

use App\Models\Athlete;
use App\Models\Category;
use App\Models\Contingent;
use App\Models\Official;
use Illuminate\Database\Seeder;

class ContingentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contingent::factory(5)
            ->create()
            ->each(function ($contingent) {
                // Add Officials
                Official::create([
                    'contingent_id' => $contingent->id,
                    'name' => fake()->name(),
                    'role' => 'Manager',
                    'phone' => fake()->phoneNumber(),
                ]);

                Official::create([
                    'contingent_id' => $contingent->id,
                    'name' => fake()->name(),
                    'role' => 'Pelatih',
                    'phone' => fake()->phoneNumber(),
                ]);

                // Create Athletes
                $athletes = Athlete::factory(rand(2, 5))->create([
                    'contingent_id' => $contingent->id
                ]);

                $totalAthleteFee = 0;
                foreach ($athletes as $athlete) {
                    // Attach categories based on match_type and gender
                    $categories = Category::where('match_type', $athlete->match_type)
                        ->where('gender', $athlete->gender)
                        ->get();

                    if ($categories->isNotEmpty()) {
                        $athlete->categories()->attach(
                            $categories->random(min(rand(1, 3), $categories->count()))->pluck('id')->toArray()
                        );
                    }
                    
                    $totalAthleteFee += 300000;
                }

                // Update final_amount (Contingent fee 300k + Athlete fees + unique code)
                $contingent->update([
                    'final_amount' => 300000 + $totalAthleteFee + $contingent->unique_code
                ]);
            });
    }
}
