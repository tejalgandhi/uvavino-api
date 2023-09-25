<?php

namespace Tests\Feature;

use App\Models\Auction;
use App\Models\Basket;
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
        $basket = Basket::factory()->create();
        $auction = Auction::factory()->create();

        return compact("wine_user","basket","auction");
    }

    public function test_get_wishlist()
    {
        $data = $this->common_factory();
        $response = $this->actingAs($data['wine_user'])->post('/api/wishlist/products',[
            'ref_id' => $data['basket']->id,
            'type' => Wishlist::PRODUCT_TYPE,
        ]);
        $response->assertStatus(201);
        $response = $this->actingAs($data['wine_user'])->post('/api/wishlist/products',[
            'ref_id' => $data['auction']->id,
            'type' => Wishlist::AUCTION_TYPE,
        ]);
        $response->assertStatus(201);
        $response = $this->actingAs($data['wine_user'])->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response = $this->actingAs($data['wine_user'])->get('/api/wishlist/products',[
            'type'=>Wishlist::PRODUCT_TYPE
        ]);
        $response->assertStatus(200);
        $response = $this->actingAs($data['wine_user'])->get('/api/wishlist/products',[
            'type'=>Wishlist::AUCTION_TYPE
        ]);
        $response->assertStatus(200);
    }

    public function test_simple_store_wishlist()
    {
        $data = $this->common_factory();
        $response =  $this->actingAs($data['wine_user'])->post('/api/wishlist/products',[
            'ref_id' => $data['basket']->id,
            'type' => Wishlist::PRODUCT_TYPE,
        ]);
        $response->assertStatus(201);
        $response = $this->actingAs($data['wine_user'])->post('/api/wishlist/products',[
            'ref_id' => $data['auction']->id,
            'type' => Wishlist::AUCTION_TYPE,
        ]);
        $response->assertStatus(201);

        $response =  $this->actingAs($data['wine_user'])->post('/api/wishlist/products',[
            'ref_id' => 400,
            'type' => Wishlist::PRODUCT_TYPE,
        ]);
        $response->assertStatus(204);

        $response =  $this->actingAs($data['wine_user'])->post('/api/wishlist/products');
        $response->assertStatus(422);
    }

    public function test_delete_wishlist()
    {
        $data = $this->common_factory();
        $response =  $this->actingAs($data['wine_user'])->post('/api/wishlist/products', [
            'ref_id' => $data['basket']->id,
            'type' => Wishlist::PRODUCT_TYPE,
        ]);
        $response->assertStatus(201);

        $response = $this->actingAs($data['wine_user'])->post('/api/wishlist/products',[
            'ref_id' => $data['auction']->id,
            'type' => Wishlist::AUCTION_TYPE,
        ]);
        $response->assertStatus(201);

        $response =  $this->actingAs($data['wine_user'])->delete('/api/wishlist/products/', [
            'ref_id' => $data['basket']->id,
            'type' => Wishlist::PRODUCT_TYPE,
        ]);
        $response->assertStatus(200);

        $response =  $this->actingAs($data['wine_user'])->delete('/api/wishlist/products/', [
            'ref_id' => $data['auction']->id,
            'type' => Wishlist::AUCTION_TYPE,
        ]);
        $response->assertStatus(200);

        $response =  $this->actingAs($data['wine_user'])->delete('/api/wishlist/products/', [
            'ref_id' => 100,
            'type' => Wishlist::PRODUCT_TYPE,
        ]);
        $response->assertStatus(200);

        $response =  $this->actingAs($data['wine_user'])->delete('/api/wishlist/products/', [
            'ref_id' => 100,
            'type' => Wishlist::AUCTION_TYPE,
        ]);
        $response->assertStatus(200);
    }

    public function test_one_user_added_product_should_not_be_shown_to_another_user()
    {
        $user1 = WineUser::factory()->create();
        $user2 = WineUser::factory()->create();
        $product = Basket::factory()->create();
        $auction = Auction::factory()->create();
        $response =  $this->actingAs($user1)->post('/api/wishlist/products', [
            'ref_id' => $product->id,
            'type' => Wishlist::PRODUCT_TYPE,
        ]);
        $response->assertStatus(201);
        $response =  $this->actingAs($user1)->post('/api/wishlist/products', [
            'ref_id' => $auction->id,
            'type' => Wishlist::AUCTION_TYPE,
        ]);
        $response->assertStatus(201);

        $response = $this->actingAs($user1)->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response->assertJsonCount(2, "data");
        $response = $this->actingAs($user2)->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response->assertJsonCount(0, "data");
    }

    public function test_delete_product_from_wishlist_dont_affect_other_user_wishlist()
    {
        $data1 = $this->common_factory();
        $data2 = $this->common_factory();
        $user1 = $data1['wine_user'];
        $user2 = $data2['wine_user'];
        $product = $data1['basket'];

        Product::factory()->create(['wine_user_id' => $data1['wine_user']->id, 'basket_id' => $data1['basket']->id]);
        Product::factory()->create(['wine_user_id' => $data2['wine_user']->id, 'basket_id' =>$data1['basket']->id]);

        Wishlist::factory()->create(['wine_user_id' =>  $data1['wine_user']->id, 'ref_id' => $data1['basket']->id,'type' => Wishlist::PRODUCT_TYPE]);
        Wishlist::factory()->create(['wine_user_id' =>  $data2['wine_user']->id, 'ref_id' => $data1['basket']->id,'type' => Wishlist::PRODUCT_TYPE]);
        $response = $this->actingAs($user1)->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response->assertJsonCount(1, "data");
        $response = $this->actingAs($user2)->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response->assertJsonCount(1, "data");

        $response =  $this->actingAs($user1)->delete('/api/wishlist/products/' , [
            'ref_id' => $product->id,
            'type' => Wishlist::PRODUCT_TYPE,
        ]);
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
        $data1 = $this->common_factory();
        $data2 = $this->common_factory();
        $user1 = $data1['wine_user'];
        $user2 = $data2['wine_user'];

        Wishlist::factory()->count(2)->create(['wine_user_id' =>  $data1['wine_user']->id, 'ref_id' => $data1['basket']->id]);
        Wishlist::factory()->count(5)->create(['wine_user_id' =>  $data2['wine_user']->id, 'ref_id' => $data1['basket']->id]);

        $response = $this->actingAs($user1)->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response->assertJsonCount(2, "data");

        $response = $this->actingAs($user2)->get('/api/wishlist/products');
        $response->assertStatus(200);
        $response->assertJsonCount(5, "data");
    }
}
