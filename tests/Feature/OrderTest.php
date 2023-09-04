<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\WineUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_new_order()
    {
        $user = WineUser::factory()->create();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->post('/api/orders', [
            "items" => [[
                "orderable_type" => "product",
                "orderable_id" => $product->id,
                "amount" => 12.55,
                "qty" => 2
            ]]
        ]);
        $response->assertExactJson(["message" => "Order has been placed successfully."]);
        $response->assertStatus(201);
    }

    public function test_invalid_order_type()
    {
        $user = WineUser::factory()->create();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->post('/api/orders', [
            "items" => [[
                "orderable_type" => "random",
                "orderable_id" => $product->id,
                "amount" => 12.55,
                "qty" => 2
            ]]
        ]);
        $response->assertStatus(422);
    }

    public function test_order_with_type_validation()
    {
        $user = WineUser::factory()->create();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->post('/api/orders', [
            "items" => [[
                "id" => $product->id,
                "amount" => 12.55,
                "qty" => 2
            ]]
        ]);
        $response->assertStatus(422);
    }

    public function test_order_with_id_validation()
    {
        $user = WineUser::factory()->create();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->post('/api/orders', [
            "items" => [[
                "type" => "product",
                "amount" => 12.55,
                "qty" => 2
            ]]
        ]);
        $response->assertStatus(422);
    }

    public function test_order_with_amount_validation()
    {
        $user = WineUser::factory()->create();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->post('/api/orders', [
            "items" => [[
                "type" => "product",
                "id" => $product->id,
                "qty" => 2
            ]]
        ]);
        $response->assertStatus(422);
    }

    public function test_order_with_qty_validation()
    {
        $user = WineUser::factory()->create();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->post('/api/orders', [
            "items" => [[
                "type" => "product",
                "id" => $product->id,
                "amount" => 12.55,
            ]]
        ]);
        $response->assertStatus(422);
    }

    public function test_order_list()
    {
        $user1 = WineUser::factory()->create();
        $user2 = WineUser::factory()->create();
        Order::factory()->count(4)->create(['wine_user_id' => $user1->id]);
        Order::factory()->count(3)->create(['wine_user_id' => $user2->id]);

        // Calling order api from user 1
        $response = $this->actingAs($user1)->get('/api/orders');
        $response->assertJsonStructure([
            "meta" => [
                "current_page",
                "last_page",
                "from",
                "to",
                "total",
                "per_page"
            ]
        ]);
        $response->assertJsonCount(4, "data");
        $response->assertStatus(200);

        // Calling the same API with user 2
        $response = $this->actingAs($user2)->get('/api/orders');
        $response->assertJsonStructure([
            "meta" => [
                "current_page",
                "last_page",
                "from",
                "to",
                "total",
                "per_page"
            ]
        ]);
        $response->assertJsonCount(3, "data");
        $response->assertStatus(200);
    }

    public function test_order_show_to_only_owner_user()
    {
        $user1 = WineUser::factory()->create();
        $user2 = WineUser::factory()->create();
        $order1 = Order::factory()->create(['wine_user_id' => $user1->id]);
        $order2 = Order::factory()->create(['wine_user_id' => $user2->id]);

        // Positive - User trying to see their own order
        $response = $this->actingAs($user1)->get('/api/orders/' . $order1->id);
        $response->assertStatus(200);
        $response = $this->actingAs($user2)->get('/api/orders/' . $order2->id);
        $response->assertStatus(200);

        // Nagetive - User trying to see others order
        $response = $this->actingAs($user1)->get('/api/orders/' . $order2->id);
        $response->assertStatus(403);
        $response = $this->actingAs($user2)->get('/api/orders/' . $order1->id);
        $response->assertStatus(403);

        // Tring to get order details as anonymous
        $response = $this->get('/api/orders/' . $order1->id);
        $response->assertStatus(403);

    }
}
