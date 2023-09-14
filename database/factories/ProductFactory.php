<?php

namespace Database\Factories;

use App\Models\Basket;
use App\Models\Country;
use App\Models\Producer;
use App\Models\Region;
use App\Models\WineTag;
use App\Models\WineType;
use App\Models\WineUser;
use App\Models\DrinkType;
use App\Models\WineVariety;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    use RandomizeTrait;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        WineTag::factory()->count(10)->create();
        WineVariety::factory()->count(10)->create();
        $wine_tags = WineTag::pluck('id')->toArray();
        $wine_variety = WineVariety::pluck('id')->toArray();
        return [
            'basket_id' => Basket::factory(),
            'wine_user_id' => $this->randomize(WineUser::class),
            'country_id' => $this->randomize(Country::class),
            'region_id' => $this->randomize(Region::class),
            'producer_id' => $this->randomize(Producer::class),
            'wine_type' => $this->randomize(WineType::class),
            'wine_name' => ucfirst(fake()->word()),
            'description' => fake()->paragraph(50),
            'bottle_size' => ucfirst(fake()->word()),
            'wine_maker' => ucfirst(fake()->word()),
            'year' => fake()->year(),
            'wine_variety' => implode(',',$wine_variety),
            'wine_tag' => implode(',',$wine_tags),
            'storage_condition' => fake()->text(),
            'review_and_rewards' => fake()->text(),
            'review_name' => fake()->text(),
            'medal' => 0,
            'award_name' => fake()->word(),
            'score' => fake()->numberBetween(11,50),
        ];
    }
}
