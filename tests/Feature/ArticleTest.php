<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ArticleTest extends TestCase
{
    use RefreshDatabase;

    private function check_structure($response) {
        $response->assertJsonStructure([
            "meta"=>["current_page","last_page","from","to","total","per_page"],
            "data" => ["*" => ["id",
                "title",
                "slug",
                "category",
                "content",
                "image",
                "images",
                "videos",
                "status",
                "date",
                "documents",
                "featured",
                "link",
                "extras",
                "extras_translatable",
            ]]
        ]);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_simple_article_listing()
    {
        $c = rand(15, 30);
        $category = Category::factory()->create();
        /* @phpstan-ignore-next-line */
        $category->factory()->hasArticles($c)->create();
        $response = $this->get('/api/articles');
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);
    }

    public function test_get_single_article()
    {
        $article = Article::factory()->create();
        $response = $this->get('/api/article/' . $article->slug);
        $response->assertStatus(200);

        $slug = 'Test';
        $response = $this->get('/api/article/' . $slug);
        $response->assertStatus(404);
    }


}
