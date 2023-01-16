<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StaticPage>
 */
class StaticPageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = fake()->sentence();
        return [
            'template' => Str::lower(fake()->word()),
            'name' => implode(" ", fake()->words()),
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraph(50)
        ];
    }
}
