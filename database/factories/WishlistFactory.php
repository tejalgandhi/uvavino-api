<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\WineUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wishlist>
 */
class WishlistFactory extends Factory
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
            'wine_user_id' => $this->randomize(WineUser::class),
            'product_id' => $this->randomize(Product::class)
        ];
    }
}
