<?php

namespace Database\Factories;

use App\Models\Auction;
use App\Models\WineUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuctionBid>
 */
class AuctionBidFactory extends Factory
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
            'auction_id' => $this->randomize(Auction::class),
            'value' => rand(100,500)
        ];
    }
}
