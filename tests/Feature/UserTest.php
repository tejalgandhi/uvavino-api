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



}
