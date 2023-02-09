<?php

namespace Tests\Feature;

use App\Models\Auction;
use App\Models\WineUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuctionTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_auction_listing_without_any_auction()
    {
        $user = WineUser::factory()->create();
        $response = $this->actingAs($user)->get('/api/auction');
        $response->assertStatus(200);
        $response->assertJsonCount(0, "data");
    }

    public function test_auction_listing_with_auction()
    {
        $user = WineUser::factory()->create();
        Auction::factory()->count(7)->create();
        $response = $this->actingAs($user)->get('/api/auction');
        $response->assertStatus(200);
        $response->assertJsonCount(7, "data");
    }

    public function test_auction_listing_with_auction_more_than_a_page()
    {
        $user = WineUser::factory()->create();
        Auction::factory()->count(17)->create();
        $response = $this->actingAs($user)->get('/api/auction');
        $response->assertStatus(200);
        $response->assertJsonCount(10, "data");
        $response = $this->actingAs($user)->get('/api/auction?page=2');
        $response->assertStatus(200);
        $response->assertJsonCount(7, "data");
    }

    public function test_auction_create()
    {
        $user = WineUser::factory()->create();
        $this->assertEquals(0, Auction::count(), "There should not be any auction present in the db.");
        $data = Auction::factory()->make()->toArray();
        $response = $this->actingAs($user)->post('/api/auction', $data);
        $this->assertEquals(1, Auction::count(), "There should not be only one auction present in the db.");
    }

    public function test_get_single_auction()
    {
        $user = WineUser::factory()->create();
        $auction = Auction::factory()->create();
        $response = $this->actingAs($user)->get('/api/auction/' . $auction->id);
        $response->assertStatus(200);
    }

    public function test_make_bid_on_auction()
    {
        $user = WineUser::factory()->create();
        $auction = Auction::factory()->create();
        $response = $this->actingAs($user)->post('/api/auction/' . $auction->id . '/bid',[
            'value' => 100
        ]);
        $response->assertStatus(201);
    }
}
