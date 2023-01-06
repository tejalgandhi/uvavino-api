<?php

namespace Tests\Feature\Models;

use App\Models\StaticPage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StaticPageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_list_of_static_pages_without_data()
    {
        $response = $this->get('/api/pages');
        $response->assertJsonPath("data", function($data) {
            return (count($data) == 0);
        });
        $response->assertStatus(200);
    }

    public function test_list_of_static_pages_with_only_single_page()
    {
        StaticPage::factory()->count(10)->create();
        $response = $this->get('/api/pages');
        $response->assertJsonPath("data", function($data) {
            return (count($data) == 10);
        });
        $response->assertJsonMissingPath("data.*.content");
        $response->assertJsonStructure(["data" => ["*" => ["title", "id", "slug"]]]);
        $response->assertStatus(200);
    }

    public function test_list_of_static_pages_with_single_page()
    {
        StaticPage::factory()->count(50)->create();
        $response = $this->get('/api/pages');
        $response->assertJsonPath("data", function($data) {
            return (count($data) == 10);
        });
        $response->assertJsonMissingPath("data.*.content");
        $response->assertJsonStructure(["data" => ["*" => ["title", "id", "slug"]]]);
        $response->assertStatus(200);
    }

    public function test_list_of_static_pages_with_last_page()
    {
        StaticPage::factory()->count(23)->create();
        $response = $this->get('/api/pages?page=3');
        $response->assertJsonPath("data", function($data) {
            return (count($data) == 3);
        });
        $response->assertJsonMissingPath("data.*.content");
        $response->assertJsonStructure(["data" => ["*" => ["title", "id", "slug"]]]);
        $response->assertStatus(200);
    }

    public function test_get_single_static_page()
    {
        $page = StaticPage::factory()->create();
        $response = $this->get('/api/pages/' . $page->slug);
        $response->assertExactJson($page->toArray());
        $response->assertJsonStructure(["title", "id", "slug", "content"]);
    }
}
