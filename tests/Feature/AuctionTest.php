<?php

namespace Tests\Feature;

use App\Models\Basket;
use App\Models\Country;
use App\Models\DrinkType;
use App\Models\Producer;
use App\Models\Product;
use App\Models\Region;
use App\Models\WineTag;
use App\Models\WineType;
use App\Models\WineUser;
use Tests\TestCase;

class AuctionTest extends TestCase
{

    private function check_structure($response) {
        $response->assertJsonStructure([
            "meta"=>["current_page","last_page","from","to","total","per_page"],
            "data" => ["*" => ["id",
                "wine_name",
                "description",
                "wine_user",
                "drink_type",
                "bottle_size",
                "country_name",
                "region_name",
                "producer_name",
                "wine_maker",
                "wine_type",
                "year",
                "grape_varieties",
                "wine_tag",
                "storage_condition",
                "review_and_rewards",
                "medal",
                "award_name",
                "score",
                "review_name"
            ]]
        ]);
    }

    private function common_factory(){
        $wine_user = WineUser::factory()->create();
        $drink_type = DrinkType::factory()->create();
        $country = Country::factory()->create();
        $region = Region::factory()->create();
        $producer = Producer::factory()->create();
        $type_of_wine = WineType::factory()->create();
        $wine_tag = WineTag::factory()->create();

        return compact("wine_user","drink_type","country","region","producer","type_of_wine","wine_tag");
    }

    //Store auction

    public function store_auction_array($params = null){
        $data = $this->common_factory();
        $product = [
            "title" => "Test wine",
            "description" => "Test wine description",
            "wine_name" => "Test wine",
            "is_private" => 1,
            "type" => 1,
            "max_price" => rand(1,100),
            "stock" => rand(1,10) * 10,
            "price" => rand(1, 80) * 10,
            "weight" => rand(10, 300),
            "dimensions" => rand(10, 50) * 10,
            "quantity" => rand(1,5),
            "wine_user_id" =>$data['wine_user']->id,
            "drink_type_id" =>$data['drink_type']->id,
            "bottle_size" =>"L",
            "country_id"=> $data['country']->id,
            "region_id" => $data['region']->id,
            "producer_id" => $data['producer']->id,
            "wine_maker" =>"Wine maker",
            "type_of_wine" =>$data['type_of_wine']->id,
            "year" =>"2022",
            "grape_varieties"=>"Wine maker",
            "wine_tag_id" =>$data['wine_tag']->id
        ];

        if(!empty($params)){
            unset($product[$params]);
        }

        return compact('product','data');
    }

    public function test_simple_store_auction()
    {
        $data = $this->store_auction_array();
        $response =  $this->actingAs($data['data']['wine_user'])->post('/api/auction', $data['product']);
        $response->assertStatus(200);
    }

    public function test_store_auction_without_drink_type_required_field_failed()
    {
        $data = $this->store_auction_array('drink_type_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/auction', $data['product']);
        $response->assertJsonValidationErrors(['drink_type_id']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_wine_name_required_field_failed()
    {
        $data = $this->store_auction_array('wine_name');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/auction', $data['product']);
        $response->assertJsonValidationErrors(['wine_name']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_bottle_size_required_field_failed()
    {
        $data = $this->store_auction_array('bottle_size');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/auction', $data['product']);
        $response->assertJsonValidationErrors(['bottle_size']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_country_required_field_failed()
    {
        $data = $this->store_auction_array('country_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/auction', $data['product']);
        $response->assertJsonValidationErrors(['country_id']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_region_required_field_failed()
    {
        $data = $this->store_auction_array('region_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/auction', $data['product']);
        $response->assertJsonValidationErrors(['region_id']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_producer_required_field_failed()
    {
        $data = $this->store_auction_array('producer_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/auction', $data['product']);

        $response->assertJsonValidationErrors(['producer_id']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_wine_maker_required_field_failed()
    {
        $data = $this->store_auction_array('wine_maker');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/auction', $data['product']);

        $response->assertJsonValidationErrors(['wine_maker']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_type_of_wine_required_field_failed()
    {
        $data = $this->store_auction_array('type_of_wine');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/auction', $data['product']);

        $response->assertJsonValidationErrors(['type_of_wine']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_year_required_field_failed()
    {
        $data = $this->store_auction_array('year');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/auction', $data['product']);
        $response->assertJsonValidationErrors(['year']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_grape_varieties_required_field_failed()
    {
        $data = $this->store_auction_array('grape_varieties');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/auction', $data['product']);

        $response->assertJsonValidationErrors(['grape_varieties']);
        $response->assertStatus(422);
    }

    public function test_store_auction_without_wine_tag_required_field_failed()
    {
        $data = $this->store_auction_array('wine_tag_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/auction', $data['product']);

        $response->assertJsonValidationErrors(['wine_tag_id']);
        $response->assertStatus(422);
    }


}
