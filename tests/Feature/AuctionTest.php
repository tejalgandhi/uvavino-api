<?php

namespace Tests\Feature;

use App\Models\Auction;
use App\Models\Basket;
use App\Models\Country;
use App\Models\DrinkType;
use App\Models\Producer;
use App\Models\Product;
use App\Models\Region;
use App\Models\WineTag;
use App\Models\WineType;
use App\Models\WineUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuctionTest extends TestCase
{
    use RefreshDatabase;


    private function common_factory()
    {
        $wine_user = WineUser::factory()->create();
        $drink_type = DrinkType::factory()->create();
        $country = Country::factory()->create();
        $region = Region::factory()->create();
        $producer = Producer::factory()->create();
        $type_of_wine = WineType::factory()->create();
        $wine_tag = WineTag::factory()->create();

        return compact("wine_user", "drink_type", "country", "region", "producer", "type_of_wine", "wine_tag");
    }


    public function store_auction_array($params = null,$array_key = null)
    {
        $data = $this->common_factory();
        $product = [
            'title' => fake()->words(rand(5,10), true),
            'description' => fake()->paragraph,
            'is_private' => 1,
            'type' => 1,
            'max_price' => rand(300,800)
        ];
        $product["basket"] = [
            "title" => "Test wine",
            "description" => "Test wine description",
            "stock" => rand(1, 10) * 10,
            "price" => rand(1, 80) * 10,
            "weight" => rand(10, 300),
            "dimensions" => rand(10, 50) * 10,
        ];
        $product["products"][] = [
            "title" => "Test wine",
            "description" => "Test wine description1",
            "grape_̥varieties" => "Test wine grape varaieties",
            "storage_condition" => "Test wine storage_condition1",
            "review_and_rewards" => "Test wine review_and_rewards1",
            "medal" => "Test medal1",
            "award_name" => "Test award_name1",
            "review_name" => "Test review_name1",
            "score" => "11",
            "wine_name" => "Test wine",
            "quantity" => rand(1, 5),
            "wine_user_id" => $data['wine_user']->id,
            "drink_type_id" => $data['drink_type']->id,
            "bottle_size" => "L",
            "country_id" => $data["country"]->id,
            "region_id" => $data["region"]->id,
            "producer_id" => $data["producer"]->id,
            "wine_maker" => "Wine maker",
            "type_of_wine" => $data['type_of_wine']->id,
            "year" => "2022",
            "grape_varieties" => "Wine maker",
            "wine_tag_id" => $data['wine_tag']->id,
            "basket_quantity" => 1,
            "unit_quantity" => 1,
            "unit" => 1,
        ];
        $product["products"][] = [
            "title" => "Test wine2",
            "description" => "Test wine description2",
            "grape_̥varieties" => "Test wine grape varaieties2",
            "storage_condition" => "Test wine storage_condition2",
            "review_and_rewards" => "Test wine review_and_rewards",
            "award_name" => "Test wine award_name2",
            "medal" => "Test wine medal2",
            "review_name" => "Test wine review_name2",
            "score" => "12",
            "wine_name" => "Test wine2",
            "quantity" => rand(1, 5),
            "wine_user_id" => $data['wine_user']->id,
            "drink_type_id" => $data['drink_type']->id,
            "bottle_size" => "L",
            "country_id" => $data["country"]->id,
            "region_id" => $data["region"]->id,
            "producer_id" => $data["producer"]->id,
            "wine_maker" => "Wine maker2",
            "type_of_wine" => $data['type_of_wine']->id,
            "year" => "2023",
            "grape_varieties" => "Wine maker2",
            "wine_tag_id" => $data['wine_tag']->id,
            "basket_quantity" => 2,
            "unit_quantity" => 2,
            "unit" => 1,

        ];
        if(!empty($params)){
            if(!empty($array_key) &&$array_key =='basket')
            {
                unset($product['basket'][$params]);
            }
            elseif (!empty($array_key) &&$array_key =='auction'){
                unset($product[$params]);
            }
            else
            {
                foreach ($product['products'] as $k=>$p) {
                    unset($product['products'][$k][$params]);
                }
            }
        }

        return compact('product', 'data');
    }


    public function test_auction_listing_without_any_auction()
    {
        $data = $this->common_factory();
        $response = $this->actingAs($data['wine_user'])->get('/api/auction');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);
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
        $data = $this->store_auction_array();
        $response = $this->actingAs($user)->post('/api/auction', $data['product']);
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


    public function test_store_auction_without_drink_type_required_field_failed()
    {
        $data = $this->store_auction_array('drink_type_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_wine_name_required_field_failed()
    {
        $data = $this->store_auction_array('wine_name');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_basket_quantity_required_field_failed()
    {
        $data = $this->store_auction_array('basket_quantity');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_bottle_size_required_field_failed()
    {
        $data = $this->store_auction_array('bottle_size');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_country_required_field_failed()
    {
        $data = $this->store_auction_array('country_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_region_required_field_failed()
    {
        $data = $this->store_auction_array('region_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_producer_required_field_failed()
    {
        $data = $this->store_auction_array('producer_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_wine_maker_required_field_failed()
    {
        $data = $this->store_auction_array('wine_maker');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_type_of_wine_required_field_failed()
    {
        $data = $this->store_auction_array('type_of_wine');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_year_required_field_failed()
    {
        $data = $this->store_auction_array('year');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_grape_varieties_required_field_failed()
    {
        $data = $this->store_auction_array('grape_̥varieties');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_wine_tag_required_field_failed()
    {
        $data = $this->store_auction_array('wine_tag_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_basket_title_required_field_failed()
    {
        $data = $this->store_auction_array('title','basket');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_basket_stock_required_field_failed()
    {
        $data = $this->store_auction_array('stock','basket');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_basket_price_required_field_failed()
    {
        $data = $this->store_auction_array('price','basket');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_basket_weight_required_field_failed()
    {
        $data = $this->store_auction_array('weight','basket');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_auction_title_required_field_failed()
    {
        $data = $this->store_auction_array('title','auction');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_auction_description_required_field_failed()
    {
        $data = $this->store_auction_array('description','auction');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_auction_is_private_required_field_failed()
    {
        $data = $this->store_auction_array('is_private','auction');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_auction_type_required_field_failed()
    {
        $data = $this->store_auction_array('type','auction');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_auction_max_price_required_field_failed()
    {
        $data = $this->store_auction_array('max_price','auction');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(422);
    }

}
