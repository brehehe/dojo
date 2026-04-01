<?php

namespace Database\Factories;

use App\Models\Athlete;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Athlete>
 */
class AthleteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contingent_id' => Contingent::factory(),
            'name' => fake()->name(),
            'nik' => fake()->numerify('################'),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'birth_date' => fake()->date('Y-m-d', '2015-01-01'),
            'weight' => fake()->numberBetween(25, 80),
            'kyu' => fake()->randomElement(['Kyu 10', 'Kyu 5', 'Kyu 1']),
            'dojo_origin' => fake()->company() . ' Dojo',
            'city' => fake()->city(),
            'bpjs_number' => fake()->numerify('#############'),
            'bpjs_status' => fake()->boolean(80),
            'match_type' => fake()->randomElement(['Pemula', 'Remaja', 'Dewasa']),
            'achievement_history' => fake()->sentence(),
        ];
    }
}
