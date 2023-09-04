<?php

namespace Database\Factories;

use App\Models\WineUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $rating= rand(1,10);
        $user = WineUser::latest()->first();
        return [

            'wine_user_id'=>$user->id,
            'rating'=>$rating,
            'review_content'=>'Test Content',
        ];
    }
}
