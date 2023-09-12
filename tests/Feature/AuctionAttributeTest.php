<?php

namespace Tests\Feature;

use Tests\TestCase;

class AuctionAttributeTest extends TestCase
{

    public function test_auction_attribute_listing()
    {
        $response = $this->get('/api/auction-attribute');
        $response->assertStatus(200);
    }

    public function test_auction_attribute_listing_with_search()
    {
        $query = 'test';
        $response = $this->get('/api/auction-attribute?query='.$query);
        $response->assertStatus(200);
    }
}
