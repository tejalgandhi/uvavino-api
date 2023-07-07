<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AuctionPackage>
 */
class AuctionPackageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = ucfirst(fake()->word());
        $amount = rand(20, 50) * 100;
        $qty = rand(1,20);
        $slug = Str::slug($name);
        return compact('name','slug' ,'amount', 'qty');
    }
}
