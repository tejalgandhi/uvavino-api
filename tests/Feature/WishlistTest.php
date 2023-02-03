<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\DrinkType;
use App\Models\Producer;
use App\Models\Product;
use App\Models\Region;
use App\Models\WineTag;
use App\Models\WineType;
use App\Models\WineUser;
use Tests\TestCase;

class WishlistTest extends TestCase
{

    public function common_factory(){
        $wine_user = WineUser::factory()->create();
        $product = Product::factory()->create();

        return compact("wine_user","product");
    }

    public function test_get_wishlist()
    {
        $data = $this->common_factory();
        $response =  $this->actingAs($data['wine_user'])->post('/api/wishlist',[
            'product_id' => $data['product']->id
        ]);
        $response->assertStatus(200);
        $response =  $this->actingAs($data['wine_user'])->get('/api/wishlist');
        $response->assertStatus(200);
    }

    public function test_simple_store_wishlist()
    {
        $data = $this->common_factory();
        $response =  $this->actingAs($data['wine_user'])->post('/api/wishlist',[
            'product_id' => $data['product']->id
        ]);
        $response->assertStatus(200);

        $response =  $this->actingAs($data['wine_user'])->post('/api/wishlist');
        $response->assertStatus(422);
    }

    public function test_delete_wishlist()
    {
        $data = $this->common_factory();
        $response =  $this->actingAs($data['wine_user'])->post('/api/wishlist?product_id='.$data['product']->id);
        $response->assertStatus(200);
        $response =  $this->actingAs($data['wine_user'])->delete('/api/wishlist?product_id='.$data['product']->id);
        $response->assertStatus(200);

        $response =  $this->actingAs($data['wine_user'])->delete('/api/wishlist?product_id=100');
        $response->assertStatus(200);
    }



}
