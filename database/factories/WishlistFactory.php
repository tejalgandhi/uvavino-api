<?php

namespace Database\Factories;

use App\Models\Basket;
use App\Models\Product;
use App\Models\WineUser;
use App\Models\Wishlist;
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
            'basket_id' => $this->randomize(Basket::class)
        ];
    }
}
