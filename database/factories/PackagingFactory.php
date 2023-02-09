<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Packaging>
 */
class PackagingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'stock' => rand(1,10) * 10,
            'price' => rand(1, 80) * 10,
            'weight' => rand(10, 300),
            'dimension' => rand(10, 50) * 10,
            'quantity' => rand(1,5),
            'unit' => (['kg', 'ml', 'L'])[rand(0,2)],
        ];
    }
}
