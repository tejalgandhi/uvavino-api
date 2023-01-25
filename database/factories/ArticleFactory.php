<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    use RandomizeTrait;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $r = rand(1, 15);
        $date = fake()->dateTimeBetween('-2 months', 'now');
        $title = fake()->text(35);

        // DEFAULT VIDEO URL
        $video_url = ['https://youtube.com/watch?v=FP_EW1zO2jo'];
        $url = 'https://youtube.com/watch?v=FP_EW1zO2jo';

        // GALLERY
        $gallery = [];
        $n = rand(4, 7);
        for ($x = 0; $x <= $n; $x++) {
            array_push($gallery, fake()->imageUrl(370, 215, true));
        }
        $category_id = $this->randomize(Category::class);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'content' => fake()->text(),
            'image' => 'http://lorempixel.com/640/480/',
            'link' => 'http://lorempixel.com/640/480/',
            'images' => $url,
            'videos' => $url,
            'documents' => 'http://lorempixel.com/640/480/',
            'status' => 'publish',
            'category_id' => $category_id,
            'featured' => 0,
            'date' => $date
//            'created_at' => $date,
//            'updated_at' => $date,
        ];
    }
}
