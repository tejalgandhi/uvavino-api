<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RecommendedProductTest extends TestCase
{

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_getting_recommended_product_normally()
    {
        // We can write test cases because to get recommended product, we have to write raw query
        // which is specific to the mysql, so can't be working with sqlite at the same time.
        $product = Product::factory()->create();
        Product::factory()->count(50)->create();
        $url = 'api/product/'.$product->slug.'/recommended';
        $response = $this->get($url);
        $response->assertStatus(200);
    }

    public function test_getting_recommended_products_from_the_product_that_does_not_exist_in_db()
    {
        $response = $this->get('api/product/test/recommended');
        $response->assertStatus(404);
    }
}
