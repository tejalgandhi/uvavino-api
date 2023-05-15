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

class ProductTest extends TestCase
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

    public function common_factory(){
        $wine_user = WineUser::factory()->create();
        $drink_type = DrinkType::factory()->create();
        $country = Country::factory()->create();
        $region = Region::factory()->create();
        $producer = Producer::factory()->create();
        $type_of_wine = WineType::factory()->create();
        $wine_tag = WineTag::factory()->create();

        return compact("wine_user","drink_type","country","region","producer","type_of_wine","wine_tag");
    }

    public function store_product_array($params = null){
        $data = $this->common_factory();
        $product = [
            "title" => "Test wine",
            "description" => "Test wine description",
            "wine_name" => "Test wine",
            "stock" =>11,
            "price" =>120,
            "weight" =>12,
            "dimensions" =>"12*11",
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

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_simple_product_listing()
    {
        $response = $this->get('/api/product');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(15, 30);
        $basket = Basket::factory()->create();
        Product::factory()->count($c)->create(['basket_id'=>$basket->id]);

        $response = $this->get('/api/product');
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);
    }

    public function test_order_products_ascending()
    {
        Product::factory()->count(20)->create();
        $response = $this->get('/api/product?order_by=ascending');
        $response->assertJsonCount(10, 'data');
        $response->assertStatus(200);
    }

    public function test_order_products_descending()
    {
        Product::factory()->count(20)->create();
        $response = $this->get('/api/product?order_by=descending');
        $response->assertJsonCount(10, 'data');
        $response->assertStatus(200);
    }


    public function test_filter_products_with_correct_wine_name()
    {
        $wine1 = 'first';
        $wine2 = 'last';
        Product::factory()->count(3)->create(['wine_name' => $wine1]);
        Product::factory()->count(15)->create(['wine_name' => $wine2]);
        // normal query with first wine name
        $response = $this->get('/api/product?wine_name='.$wine1);
        $response->assertJsonCount(3, 'data');
        $this->check_structure($response);

        // Normal query with second wine name
        $response->assertStatus(200);
        $response = $this->get('/api/product?wine_name='.$wine2);
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        // change first wine name adding some extra chars
        $response = $this->get('/api/product?wine_name=firstc');
        $response->assertJsonCount(0, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        // change first wine name removing some extra chars
        $response = $this->get('/api/product?wine_name=fir');
        $response->assertJsonCount(3, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        // query wine name with combination
        $response = $this->get('/api/product?wine_name=st');
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);
    }

    public function test_filter_products_with_drink_type()
    {
        Product::factory()->count(20)->create();
        $drink_type = DrinkType::factory()->create();
        $response = $this->get('/api/product?drink_type_id='.$drink_type->id);
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        Product::factory()->count(3)->create(['drink_type_id' => $drink_type->id]);
        $response = $this->get('/api/product?drink_type_id='.$drink_type->id);
        $response->assertJsonCount(3, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $drink_type2 = DrinkType::factory()->create();
        Product::factory()->count(9)->create(['drink_type_id' => $drink_type2->id]);
        $response = $this->get('/api/product?drink_type_id[]='.$drink_type->id.'&drink_type_id[]='.$drink_type2->id);
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $drink_type3 = DrinkType::factory()->create();
        Product::factory()->count(4)->create(['drink_type_id' => $drink_type3->id]);
        $response = $this->get('/api/product?drink_type_id[]='.$drink_type->id.'&drink_type_id[]='.$drink_type3->id);
        $response->assertJsonCount(7, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);
    }

    public function test_filter_products_with_country()
    {
        Product::factory()->count(20)->create();
        $country = Country::factory()->create();
        $response = $this->get('/api/product?country_id='.$country->id);
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        Product::factory()->count(3)->create(['country_id' => $country->id]);
        $response = $this->get('/api/product?country_id='.$country->id);
        $response->assertJsonCount(3, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $country2 = Country::factory()->create();
        Product::factory()->count(9)->create(['country_id' => $country2->id]);
        $response = $this->get('/api/product?country_id[]='.$country->id.'&country_id[]='.$country2->id);
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $country3 = Country::factory()->create();
        Product::factory()->count(4)->create(['country_id' => $country3->id]);
        $response = $this->get('/api/product?country_id[]='.$country->id.'&country_id[]='.$country3->id);
        $response->assertJsonCount(7, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);
    }

    public function test_filter_products_with_region()
    {
        Product::factory()->count(20)->create();
        $region = Region::factory()->create();
        $response = $this->get('/api/product?region_id='.$region->id);
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        Product::factory()->count(3)->create(['region_id' => $region->id]);
        $response = $this->get('/api/product?region_id='.$region->id);
        $response->assertJsonCount(3, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $region2 = Region::factory()->create();
        Product::factory()->count(9)->create(['region_id' => $region2->id]);
        $response = $this->get('/api/product?region_id[]='.$region->id.'&region_id[]='.$region2->id);
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $region3 = Region::factory()->create();
        Product::factory()->count(4)->create(['region_id' => $region3->id]);
        $response = $this->get('/api/product?region_id[]='.$region->id.'&region_id[]='.$region3->id);
        $response->assertJsonCount(7, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);
    }

    public function test_filter_products_with_producer()
    {
        Product::factory()->count(20)->create();
        $producer = Producer::factory()->create();
        $response = $this->get('/api/product?producer_id='.$producer->id);
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        Product::factory()->count(3)->create(['producer_id' => $producer->id]);
        $response = $this->get('/api/product?producer_id='.$producer->id);
        $response->assertJsonCount(3, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $producer2 = Producer::factory()->create();
        Product::factory()->count(9)->create(['producer_id' => $producer2->id]);
        $response = $this->get('/api/product?producer_id[]='.$producer->id.'&producer_id[]='.$producer2->id);
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $producer3 = Producer::factory()->create();
        Product::factory()->count(4)->create(['producer_id' => $producer3->id]);
        $response = $this->get('/api/product?producer_id[]='.$producer->id.'&producer_id[]='.$producer3->id);
        $response->assertJsonCount(7, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);
    }

    public function test_filter_products_with_wine_type()
    {
        Product::factory()->count(20)->create();
        $wine_type = WineType::factory()->create();
        $response = $this->get('/api/product?type_of_wine='.$wine_type->id);
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        Product::factory()->count(3)->create(['type_of_wine' => $wine_type->id]);
        $response = $this->get('/api/product?type_of_wine='.$wine_type->id);
        $response->assertJsonCount(3, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $wine_type2 = WineType::factory()->create();
        Product::factory()->count(9)->create(['type_of_wine' => $wine_type2->id]);
        $response = $this->get('/api/product?type_of_wine[]='.$wine_type->id.'&type_of_wine[]='.$wine_type2->id);
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $wine_type3 = WineType::factory()->create();
        Product::factory()->count(4)->create(['type_of_wine' => $wine_type3->id]);
        $response = $this->get('/api/product?type_of_wine[]='.$wine_type->id.'&type_of_wine[]='.$wine_type3->id);
        $response->assertJsonCount(7, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);
    }

    public function test_filter_products_with_wine_tag()
    {
        Product::factory()->count(20)->create();
        $wine_tag = WineTag::factory()->create();
        $response = $this->get('/api/product?wine_tag_id='.$wine_tag->id);
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        Product::factory()->count(3)->create(['wine_tag_id' => $wine_tag->id]);
        $response = $this->get('/api/product?wine_tag_id='.$wine_tag->id);
        $response->assertJsonCount(3, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $wine_tag2 = WineTag::factory()->create();
        Product::factory()->count(9)->create(['wine_tag_id' => $wine_tag2->id]);
        $response = $this->get('/api/product?wine_tag_id[]='.$wine_tag->id.'&wine_tag_id[]='.$wine_tag2->id);
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $wine_tag3 = WineTag::factory()->create();
        Product::factory()->count(4)->create(['wine_tag_id' => $wine_tag3->id]);
        $response = $this->get('/api/product?wine_tag_id[]='.$wine_tag->id.'&wine_tag_id[]='.$wine_tag3->id);
        $response->assertJsonCount(7, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);
    }


    public function test_get_single_product()
    {
        $basket = Basket::factory()->create();
        Product::factory()->create(['basket_id'=>$basket->id]);
        $response = $this->get('/api/product/' . $basket->slug);
        $response->assertJsonStructure(["id",
            "wine_name",
            "description",
            "wine_user_id",
            "drink_type_id",
            "bottle_size",
            "country_id",
            "region_id",
            "producer_id",
            "wine_maker",
            "type_of_wine",
            "year",
            "grape_varieties",
            "wine_tag_id",
            "storage_condition",
            "review_and_rewards",
            "medal",
            "award_name",
            "score",
            "review_name"
         ]);
        $response->assertStatus(200);

        $slug = 'Test';
        $response = $this->get('/api/product/' . $slug);
        $response->assertStatus(404);
    }


    public function test_simple_store_product()
    {
        $data = $this->store_product_array();
        $response =  $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertStatus(200);
    }

    public function test_store_product_without_drink_type_required_field_failed()
    {
        $data = $this->store_product_array('drink_type_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertJsonValidationErrors(['drink_type_id']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_wine_name_required_field_failed()
    {
        $data = $this->store_product_array('wine_name');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertJsonValidationErrors(['wine_name']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_bottle_size_required_field_failed()
    {
        $data = $this->store_product_array('bottle_size');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertJsonValidationErrors(['bottle_size']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_country_required_field_failed()
    {
        $data = $this->store_product_array('country_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertJsonValidationErrors(['country_id']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_region_required_field_failed()
    {
        $data = $this->store_product_array('region_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertJsonValidationErrors(['region_id']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_producer_required_field_failed()
    {
        $data = $this->store_product_array('producer_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);

        $response->assertJsonValidationErrors(['producer_id']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_wine_maker_required_field_failed()
    {
        $data = $this->store_product_array('wine_maker');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);

        $response->assertJsonValidationErrors(['wine_maker']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_type_of_wine_required_field_failed()
    {
        $data = $this->store_product_array('type_of_wine');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);

        $response->assertJsonValidationErrors(['type_of_wine']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_year_required_field_failed()
    {
        $data = $this->store_product_array('year');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);
        $response->assertJsonValidationErrors(['year']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_grape_varieties_required_field_failed()
    {
        $data = $this->store_product_array('grape_varieties');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);

        $response->assertJsonValidationErrors(['grape_varieties']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_wine_tag_required_field_failed()
    {
        $data = $this->store_product_array('wine_tag_id');
        $response = $this->actingAs($data['data']['wine_user'])->post('/api/product', $data['product']);

        $response->assertJsonValidationErrors(['wine_tag_id']);
        $response->assertStatus(422);
    }


}
