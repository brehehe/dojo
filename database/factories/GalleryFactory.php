<?php

namespace Database\Factories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'image_url' => 'https://picsum.photos/seed/'.rand(1, 100).'/600/600',
            'category' => $this->faker->randomElement(['Highlight', 'Competition', 'Awarding']),
        ];
    }
}
