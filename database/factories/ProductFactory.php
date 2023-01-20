<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\Producer;
use App\Models\Region;
use App\Models\WineTag;
use App\Models\WineType;
use App\Models\WineUser;
use App\Models\DrinkType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $wine_user_id = WineUser::factory();
        $country_id = Country::factory();
        $region_id = Region::factory();
        $producer_id = Producer::factory();
        $type_of_wine = WineType::factory();
        $drink_type_id = DrinkType::factory();
        $wine_tag_id = WineTag::factory();
        $wine_name = ucfirst(fake()->word());
        $slug = str_replace(" ","_",fake()->word());
        $description = fake()->paragraph(50);
        $bottle_size = ucfirst(fake()->word());
        $wine_maker = ucfirst(fake()->word());
        $year = fake()->year();
        $grape_varieties = fake()->text();
        $storage_condition = fake()->text();
        $review_and_rewards = fake()->text();
        $review_name = fake()->text();
        $medal = fake()->word();
        $award_name = fake()->word();
        $score = fake()->numberBetween(11,50);
        return compact('wine_name',"slug",'wine_user_id', 'description','bottle_size','drink_type_id','country_id','region_id','producer_id',
            'wine_maker','type_of_wine','year','grape_varieties','wine_tag_id','storage_condition','review_and_rewards','medal','award_name','score','review_name' );

    }
}
