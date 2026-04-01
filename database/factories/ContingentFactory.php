<?php

namespace Database\Factories;

use App\Models\Contingent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Contingent>
 */
class ContingentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Kontingen ' . fake()->city(),
            'leader_name' => fake()->name(),
            'leader_phone' => fake()->phoneNumber(),
            'leader_email' => fake()->safeEmail(),
            'address' => fake()->address(),
            'payment_method' => fake()->randomElement(['BCA', 'Mandiri', 'QRIS']),
            'unique_code' => fake()->numberBetween(100, 999),
            'final_amount' => 0,
        ];
    }
}
