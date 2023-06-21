<?php

namespace Database\Factories;

use App\Models\Basket;
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
            'basket_id' => Basket::factory(),
            'title' => fake()->words(rand(5,10), true),
            'description' => fake()->paragraph,
            'is_private' => 1,
            'type' => 1,
            'max_price' => rand(300,800)
        ];
    }
}
