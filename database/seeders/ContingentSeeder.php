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
                // Add Officials (Now master data, linked via registration_official pivot later)
                Official::create([
                    'name' => fake()->name(),
                    'phone' => fake()->phoneNumber(),
                ]);

                Official::create([
                    'name' => fake()->name(),
                    'phone' => fake()->phoneNumber(),
                ]);

                // Create Athletes (Master Data)
                $athletes = Athlete::factory(rand(5, 10))->create();

                // Assign to Contingent as Primary Members
                foreach ($athletes as $athlete) {
                    $athlete->contingents()->attach($contingent->id, [
                        'is_primary' => true,
                        'joined_at' => now(),
                    ]);

                    // Add some history
                    $athlete->contingentHistories()->create([
                        'contingent_id' => $contingent->id,
                        'moved_at' => now(),
                        'notes' => 'Seeded as initial member',
                    ]);
                }
            });
    }
}
