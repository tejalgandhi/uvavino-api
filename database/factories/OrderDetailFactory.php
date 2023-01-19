<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetail>
 */
class OrderDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $orderable = $this->getOrderable();

        return [
            'order_id' => Order::factory(),
            'orderable_id' => $orderable::factory(),
            'orderable_type' => $orderable,
            'amount' => fake()->randomNumber(2000, 50000) / 100,
            'qty' => fake()->randomNumber(1, 20),
        ];
    }

    private function getOrderable() {
        return Product::class;
        // return fake()->randomElement(Product::class);
    }
}
