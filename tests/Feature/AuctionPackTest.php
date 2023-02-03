<?php

namespace Tests\Feature;

use App\Models\AuctionPackage;
use App\Models\WineUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuctionPackTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_getting_auction_packs()
    {
        $user = WineUser::factory()->create();
        AuctionPackage::factory()->count(10)->create();
        $response = $this->actingAs($user)->get('/api/auction-packs');
        $response->assertStatus(200);
        $response->assertJsonCount(10);
    }

    public function test_without_auction_pack_in_db()
    {
        $user = WineUser::factory()->create();
        $response = $this->actingAs($user)->get('/api/auction-packs');
        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    public function test_with_hundrades_of_auction_pack_in_db()
    {
        $user = WineUser::factory()->create();
        $count = rand(200, 500);
        AuctionPackage::factory()->count($count)->create();
        $response = $this->actingAs($user)->get('/api/auction-packs');
        $response->assertStatus(200);
        $response->assertJsonCount($count);
    }
}
