<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\WineUser;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Producer>
 */
class ProducerFactory extends Factory
{
    use RandomizeTrait;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $wine_user_id = $this->randomize(WineUser::class);
        $country_id = $this->randomize(Country::class);
        $name = ucfirst(fake()->word());
        $slug = Str::slug($name);
        return compact('wine_user_id', 'country_id', 'name', 'slug');
    }
}
