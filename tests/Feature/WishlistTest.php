<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\DrinkType;
use App\Models\Producer;
use App\Models\Product;
use App\Models\Region;
use App\Models\User;
use App\Models\WineTag;
use App\Models\WineType;
use App\Models\WineUser;
use App\Models\Wishlist;
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
        $response = $this->actingAs($data['wine_user'])->post('/api/wishlist/products',[
            'product_id' => $data['product']->id
        ]);
        $response->assertStatus(201);
        $response = $this->actingAs($data['wine_user'])->get('/api/wishlist/products');
        $response->assertStatus(200);
    }

    public function test_simple_store_wishlist()
    {
        $data = $this->common_factory();
        $response =  $this->actingAs($data['wine_user'])->post('/api/wishlist/products',[
            'product_id' => $data['product']->id
        ]);
        $response->assertStatus(201);

        $response =  $this->actingAs($data['wine_user'])->post('/api/wishlist/products');
        $response->assertStatus(422);
    }

    public function test_delete_wishlist()
    {
        $data = $this->common_factory();
        $response =  $this->actingAs($data['wine_user'])->post('/api/wishlist/products', [
            'product_id' => $data['product']->id
        ]);
        $response->assertStatus(201);
        $response =  $this->actingAs($data['wine_user'])->delete('/api/wishlist/products/'.$data['product']->id);
        $response->assertStatus(200);

        $response =  $this->actingAs($data['wine_user'])->delete('/api/wishlist/products/100');
        $response->assertStatus(200);
    }

    public function test_one_user_added_product_should_not_be_shown_to_another_user()
    {
        $user1 = WineUser::factory()->create();
        $user2 = WineUser::factory()->create();
        $product = Product::factory()->create();
        $response =  $this->actingAs($user1)->post('/api/wishlist/products', [
            'product_id' => $product->id
        ]);
        $response->assertStatus(201);
        $response = $this->actingAs($user1)->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response->assertJsonCount(1, "data");
        $response = $this->actingAs($user2)->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response->assertJsonCount(0, "data");
    }

    public function test_delete_product_from_wishlist_dont_affect_other_user_wishlist()
    {
        $user1 = WineUser::factory()->create();
        $user2 = WineUser::factory()->create();
        $product = Product::factory()->create();
        Wishlist::factory()->create(['wine_user_id' => $user1->id, 'product_id' => $product->id]);
        Wishlist::factory()->create(['wine_user_id' => $user2->id, 'product_id' => $product->id]);

        $response = $this->actingAs($user1)->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response->assertJsonCount(1, "data");
        $response = $this->actingAs($user2)->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response->assertJsonCount(1, "data");

        $response =  $this->actingAs($user1)->delete('/api/wishlist/products/' . $product->id);
        $response->assertStatus(200);

        $response = $this->actingAs($user1)->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response->assertJsonCount(0, "data");
        $response = $this->actingAs($user2)->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response->assertJsonCount(1, "data");
    }

    public function test_wishlist_showing_only_logged_in_users_wishlist_data()
    {
        $user1 = WineUser::factory()->create();
        Wishlist::factory()->count(5)->create(['wine_user_id' => $user1]);
        $user2 = WineUser::factory()->create();
        Wishlist::factory()->count(3)->create(['wine_user_id' => $user2]);
        $response = $this->actingAs($user1)->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response->assertJsonCount(5, "data");
        $response = $this->actingAs($user2)->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response->assertJsonCount(3, "data");
    }
}
