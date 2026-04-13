<?php

namespace Database\Factories;

use App\Models\Athlete;
use App\Models\Contingent;
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
            'name' => fake()->name(),
            'nik' => fake()->numerify('################'),
            'gender' => fake()->randomElement(['Male', 'Female']),
            'birth_date' => fake()->date('Y-m-d', '2015-01-01'),
            'bpjs_number' => fake()->numerify('#############'),
            'bpjs_status' => fake()->boolean(80),
            'achievement_history' => fake()->sentence(),
        ];
    }
}
