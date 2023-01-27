<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $r= rand(1,10);
        $u= rand(1,10);
        $rating= rand(1,10);
        return [
            'ref_type'=>Review::PRODUCT_REF_TYPE,
            'ref_id'=>$r,
            'user_id'=>$u,
            'rating'=>$rating,
            'review_content'=>'Test Content',
        ];
    }
}
