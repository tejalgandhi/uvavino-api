<?php

namespace Tests\Feature;

use App\Models\Brand;
use App\Models\Country;
use App\Models\DrinkType;
use App\Models\Producer;
use App\Models\Region;
use App\Models\WineTag;
use App\Models\WineType;
use App\Models\WineVariety;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductAttributeTest extends TestCase
{
    public function test_drink_type_attribute_listing()
    {
        $response = $this->get('/api/product-attribute/drink-type');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        DrinkType::factory()->count($c)->create();

        $response = $this->get('/api/product-attribute/drink-type');
        $response->assertJsonCount($c, 'data');
        $response->assertJsonStructure([
            "data" => ["*" => ["id", "name", "slug", "status", "is_approved"]]
        ]);
        $response->assertStatus(200);
    }

    public function test_wine_type_attribute_listing()
    {
        $response = $this->get('/api/product-attribute/wine-type');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        WineType::factory()->count($c)->create();

        $response = $this->get('/api/product-attribute/wine-type');
        $response->assertJsonCount($c, 'data');
        $response->assertJsonStructure([
            "data" => ["*" => ["id", "name", "slug", "status", "is_approved"]]
        ]);
        $response->assertStatus(200);
    }

    public function test_countries_attribute_listing()
    {
        $response = $this->get('/api/product-attribute/country');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        Country::factory()->count($c)->create();

        $response = $this->get('/api/product-attribute/country');
        $response->assertJsonCount($c, 'data');
        $response->assertJsonStructure([
            "data" => ["*" => ["id", "name", "slug", "status"]]
        ]);
        $response->assertStatus(200);
    }

    public function test_wine_variety_attribute_listing()
    {
        $response = $this->get('/api/product-attribute/wine-variety');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        WineVariety::factory()->count($c)->create();

        $response = $this->get('/api/product-attribute/wine-variety');
        $response->assertJsonCount($c, 'data');
        $response->assertJsonStructure([
            "data" => ["*" => ["id", "name", "slug", "status", "is_approved"]]
        ]);
        $response->assertStatus(200);
    }

    public function test_wine_tag_attribute_listing()
    {
        $response = $this->get('/api/product-attribute/wine-tag');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        WineTag::factory()->count($c)->create();

        $response = $this->get('/api/product-attribute/wine-tag');
        $response->assertJsonCount($c, 'data');
        $response->assertJsonStructure([
            "data" => ["*" => ["id", "name", "slug", "status", "is_approved"]]
        ]);
        $response->assertStatus(200);
    }

    public function test_region_attribute_listing()
    {
        $response = $this->get('/api/product-attribute/region');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        Region::factory()->count($c)->create();

        $response = $this->get('/api/product-attribute/region');
        $response->assertJsonCount($c, 'data');
        $response->assertJsonStructure([
            "data" => ["*" => ["id", "country_id", "name", "slug", "status"]]
        ]);
        $response->assertStatus(200);
    }

    public function test_brand_attribute_listing()
    {
        $response = $this->get('/api/product-attribute/brand');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        Brand::factory()->count($c)->create();

        $response = $this->get('/api/product-attribute/brand');
        $response->assertJsonCount($c, 'data');
        $response->assertJsonStructure([
            "data" => ["*" => ["name", "slug", "user_id", "username", "country", "country_id"]]
        ]);
        $response->assertStatus(200);
    }

    public function test_producer_attribute_listing()
    {
        $response = $this->get('/api/product-attribute/producer');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        Producer::factory()->count($c)->create();

        $response = $this->get('/api/product-attribute/producer');
        $response->assertJsonCount($c, 'data');
        $response->assertJsonStructure([
            "data" => ["*" => ["name", "slug", "user_id", "username", "country", "country_id"]]
        ]);
        $response->assertStatus(200);
    }

    public function test_unknown_attribute_listing()
    {
        $response = $this->get('/api/product-attribute/random');
        // $response->assertJsonCount(0, 'data');
        $response->assertStatus(404);
    }
}
