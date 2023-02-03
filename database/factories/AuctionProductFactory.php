<?php

namespace Database\Factories;

use App\Models\Auction;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuctionProduct>
 */
class AuctionProductFactory extends Factory
{
    use RandomizeTrait;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'auction_id' => $this->randomize(Auction::class),
            'product_id' => $this->randomize(Product::class)
        ];
    }
}
