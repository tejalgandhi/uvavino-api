<?php

namespace Database\Factories;

use App\Models\WineUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'wine_user_id' => WineUser::factory(),
            'due_date' => now()->addDay(fake()->randomNumber(10,30)),
        ];
    }
}
