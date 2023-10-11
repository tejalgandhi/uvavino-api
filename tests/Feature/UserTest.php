<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Auction;
use App\Models\Basket;
use App\Models\Category;
use App\Models\Product;
use App\Models\WineUser;
use Tests\TestCase;


class UserTest extends TestCase
{


    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_user_product_count_listing()
    {
        $c = rand(15, 30);
        $basket = Basket::factory()->create();
        Product::factory()->count($c)->create(['basket_id' => $basket->id]);
        $user = WineUser::factory()->create();
        Auction::factory()->count(7)->create();
        $response = $this->actingAs($user)->get('/api/user/product-count');
        $response->assertStatus(201);
    }

    public function test_user_product_wishlist_count_listing()
    {
        $c = rand(15, 30);
        $basket = Basket::factory()->create();
        Product::factory()->count($c)->create(['basket_id' => $basket->id]);
        $user = WineUser::factory()->create();
        Auction::factory()->count(7)->create();
        $response = $this->actingAs($user)->get('/api/user/wishlist-count');
        $response->assertStatus(201);
    }

    public function test_get_buy_purchase_history()
    {
        $user = WineUser::factory()->create();
        $auction = Auction::factory()->create();
        $response = $this->actingAs($user)->post('/api/auction/'.$auction->id.'/bid',[
            'price'=>100
        ]);
        $response->assertStatus(201);
        $response = $this->actingAs($user)->get('/api/user/buy-purchse-history');
        $response->assertStatus(201);

    }

    public function test_get_private_auctions()
    {
        $user = WineUser::factory()->create();
        $auction = Auction::factory()->create(['is_private'=>'1']);
        $response = $this->actingAs($user)->post('/api/auction/'.$auction->id.'/bid',[
            'price'=>100
        ]);
        $response->assertStatus(201);
        $response = $this->actingAs($user)->get('/api/user/private-auctions');
        $response->assertStatus(201);

    }
    public function test_get_participate_auctions()
    {
        $user = WineUser::factory()->create();
        $auction = Auction::factory()->create(['is_private'=>'1']);
        $response = $this->actingAs($user)->get('/api/user/participate-auctions?user_id='.$user->id);
        $response->assertStatus(201);

    }

}
