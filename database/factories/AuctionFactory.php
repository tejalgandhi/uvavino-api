<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Auction>
 */
class AuctionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'lot_type' => 1 + (rand(1, 100) % 2),
            'title' => implode(" ", fake()->words(rand(5,10))),
            'description' => fake()->paragraph,
            'is_private' => str(rand(1, 100) % 2),
            'auction_type' => str(rand(1, 100) % 2),
            'max_price' => rand(300,800)
        ];
    }
}
