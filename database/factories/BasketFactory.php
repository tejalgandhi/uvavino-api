<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WineUser>
 */
class BasketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => ucfirst(fake()->word()),
            'description' => fake()->paragraph(50),
            'stock' => fake()->numberBetween(1,10),
            'price' => fake()->numberBetween(11,300),
            'weight' => fake()->numberBetween(1,12),
            'dimensions' => fake()->numberBetween(1,12),
        ];
    }
}
