<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = fake()->sentence();
        $country_id = Country::factory();
        $wine_user_id = UserFactory::factory();
        $drink_type_id = UserFactory::factory();
        return [
            'wine_user_id' => $wine_user_id,
            'title' => $title,
            'description' => fake()->paragraph(50)
        ];
    }
}
