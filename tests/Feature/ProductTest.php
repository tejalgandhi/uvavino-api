<?php

namespace Tests\Feature;

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

        $c = rand(10, 30);
        Product::factory()->count($c)->create();

        $response = $this->get('/api/product');
        $response->assertJsonCount(10, 'data');
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
        $response->assertStatus(200);
    }

    public function test_filter_wine_name_product_listing()
    {
        $response = $this->get('/api/product');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        Product::factory()->count($c)->create();
        $user = WineUser::factory()->create();
        $product = Product::factory()->create(['wine_user_id' => $user->id]);
        $response = $this->get('/api/product', ['wine_name' => $product->wine_name]);
        $response->assertJsonCount(1, 'data');
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
        $response->assertStatus(200);
    }

    public function test_filter_drink_type_product_listing()
    {
        $response = $this->get('/api/product');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        Product::factory()->count($c)->create();
        $drink_type = DrinkType::factory()->create();
        $response = $this->get('/api/product', ['drink_type_id' => $drink_type->id]);
        $response->assertJsonCount(10, 'data');
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
        $response->assertStatus(200);
    }

    public function test_filter_country_product_listing()
    {
        $response = $this->get('/api/product');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        Product::factory()->count($c)->create();
        $country = Country::factory()->create();
        $response = $this->get('/api/product', ['country_id' => $country->id]);
        $response->assertJsonCount(10, 'data');
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
        $response->assertStatus(200);
    }

    public function test_filter_region_product_listing()
    {
        $response = $this->get('/api/product');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        Product::factory()->count($c)->create();
        $region = Region::factory()->create();
        $response = $this->get('/api/product', ['region_id' => $region->id]);
        $response->assertJsonCount(10, 'data');
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
        $response->assertStatus(200);
    }

    public function test_filter_producer_product_listing()
    {
        $response = $this->get('/api/product');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        Product::factory()->count($c)->create();
        $producer = Producer::factory()->create();
        $response = $this->get('/api/product', ['producer_id' => $producer->id]);
        $response->assertJsonCount(10, 'data');
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
        $response->assertStatus(200);
    }

    public function test_filter_type_of_wine_product_listing()
    {
        $response = $this->get('/api/product');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        Product::factory()->count($c)->create();
        $type_of_wine = DrinkType::factory()->create();
        $response = $this->get('/api/product', ['type_of_wine' => $type_of_wine->id]);
        $response->assertJsonCount(10, 'data');
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
        $response->assertStatus(200);
    }

    public function test_filter_wine_tag_product_listing()
    {
        $response = $this->get('/api/product');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        Product::factory()->count($c)->create();
        $wine_tag = WineTag::factory()->create();
        $response = $this->get('/api/product', ['wine_tag_id' => $wine_tag->id]);
        $response->assertJsonCount(10, 'data');
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
        $response->assertStatus(200);
    }

    public function test_get_single_product()
    {
        $page = Product::factory()->create();
        $response = $this->get('/api/product/' . $page->id);
        $response->assertJson($page->toArray());
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

    public function test_simple_store_product()
    {
        $data = $this->common_factory();
        $response =  $this->actingAs($data['wine_user'])->post('/api/product', [
            "wine_name" => "Test wine",
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
        ]);
        $response->assertStatus(200);
    }

    public function test_store_product_without_drink_type_required_field_failed()
    {
        $data = $this->common_factory();
        $response = $this->actingAs($data['wine_user'])->post('/api/product', [
            "wine_name" => "Test wine",
            "wine_user_id" =>$data['wine_user']->id,
            "bottle_size" =>"L",
            "country_id"=> $data['country']->id,
            "region_id" => $data['region']->id,
            "producer_id" => $data['producer']->id,
            "wine_maker" =>"Wine maker",
            "type_of_wine" =>$data['type_of_wine']->id,
            "year" =>"2022",
            "grape_varieties"=>"Wine maker",
            "wine_tag_id" =>$data['wine_tag']->id
        ]);

        $response->assertJsonValidationErrors(['drink_type_id']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_wine_name_required_field_failed()
    {
        $data = $this->common_factory();
        $response = $this->actingAs($data['wine_user'])->post('/api/product', [
            "drink_type_id" =>$data['drink_type']->id,
            "wine_user_id" =>$data['wine_user']->id,
            "bottle_size" =>"L",
            "country_id"=> $data['country']->id,
            "region_id" => $data['region']->id,
            "producer_id" => $data['producer']->id,
            "wine_maker" =>"Wine maker",
            "type_of_wine" =>$data['type_of_wine']->id,
            "year" =>"2022",
            "grape_varieties"=>"Wine maker",
            "wine_tag_id" =>$data['wine_tag']->id
        ]);

        $response->assertJsonValidationErrors(['wine_name']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_bottle_size_required_field_failed()
    {
        $data = $this->common_factory();
        $response = $this->actingAs($data['wine_user'])->post('/api/product', [
            "wine_name" => "Test wine",
            "drink_type_id" =>$data['drink_type']->id,
            "wine_user_id" =>$data['wine_user']->id,
            "country_id"=> $data['country']->id,
            "region_id" => $data['region']->id,
            "producer_id" => $data['producer']->id,
            "wine_maker" =>"Wine maker",
            "type_of_wine" =>$data['type_of_wine']->id,
            "year" =>"2022",
            "grape_varieties"=>"Wine maker",
            "wine_tag_id" =>$data['wine_tag']->id
        ]);

        $response->assertJsonValidationErrors(['bottle_size']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_country_required_field_failed()
    {
        $data = $this->common_factory();
        $response = $this->actingAs($data['wine_user'])->post('/api/product', [
            "wine_name" => "Test wine",
            "drink_type_id" =>$data['drink_type']->id,
            "wine_user_id" =>$data['wine_user']->id,
            "bottle_size" =>"L",
            "region_id" => $data['region']->id,
            "producer_id" => $data['producer']->id,
            "wine_maker" =>"Wine maker",
            "type_of_wine" =>$data['type_of_wine']->id,
            "year" =>"2022",
            "grape_varieties"=>"Wine maker",
            "wine_tag_id" =>$data['wine_tag']->id
        ]);

        $response->assertJsonValidationErrors(['country_id']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_region_required_field_failed()
    {
        $data = $this->common_factory();
        $response = $this->actingAs($data['wine_user'])->post('/api/product', [
            "wine_name" => "Test wine",
            "drink_type_id" =>$data['drink_type']->id,
            "wine_user_id" =>$data['wine_user']->id,
            "bottle_size" =>"L",
            "country_id"=> $data['country']->id,
            "producer_id" => $data['producer']->id,
            "wine_maker" =>"Wine maker",
            "type_of_wine" =>$data['type_of_wine']->id,
            "year" =>"2022",
            "grape_varieties"=>"Wine maker",
            "wine_tag_id" =>$data['wine_tag']->id
        ]);

        $response->assertJsonValidationErrors(['region_id']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_producer_required_field_failed()
    {
        $data = $this->common_factory();
        $response = $this->actingAs($data['wine_user'])->post('/api/product', [
            "wine_name" => "Test wine",
            "drink_type_id" =>$data['drink_type']->id,
            "wine_user_id" =>$data['wine_user']->id,
            "bottle_size" =>"L",
            "country_id"=> $data['country']->id,
            "region_id" => $data['region']->id,
            "wine_maker" =>"Wine maker",
            "type_of_wine" =>$data['type_of_wine']->id,
            "year" =>"2022",
            "grape_varieties"=>"Wine maker",
            "wine_tag_id" =>$data['wine_tag']->id
        ]);

        $response->assertJsonValidationErrors(['producer_id']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_wine_maker_required_field_failed()
    {
        $data = $this->common_factory();
        $response = $this->actingAs($data['wine_user'])->post('/api/product', [
            "wine_name" => "Test wine",
            "drink_type_id" =>$data['drink_type']->id,
            "wine_user_id" =>$data['wine_user']->id,
            "bottle_size" =>"L",
            "country_id"=> $data['country']->id,
            "region_id" => $data['region']->id,
            "producer_id" => $data['producer']->id,
            "type_of_wine" =>$data['type_of_wine']->id,
            "year" =>"2022",
            "grape_varieties"=>"Wine maker",
            "wine_tag_id" =>$data['wine_tag']->id
        ]);

        $response->assertJsonValidationErrors(['wine_maker']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_type_of_wine_required_field_failed()
    {
        $data = $this->common_factory();
        $response = $this->actingAs($data['wine_user'])->post('/api/product', [
            "wine_name" => "Test wine",
            "drink_type_id" =>$data['drink_type']->id,
            "wine_user_id" =>$data['wine_user']->id,
            "bottle_size" =>"L",
            "country_id"=> $data['country']->id,
            "region_id" => $data['region']->id,
            "producer_id" => $data['producer']->id,
            "wine_maker" =>"Wine maker",
            "year" =>"2022",
            "grape_varieties"=>"Wine maker",
            "wine_tag_id" =>$data['wine_tag']->id
        ]);

        $response->assertJsonValidationErrors(['type_of_wine']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_year_required_field_failed()
    {
        $data = $this->common_factory();
        $response = $this->actingAs($data['wine_user'])->post('/api/product', [
            "wine_name" => "Test wine",
            "drink_type_id" =>$data['drink_type']->id,
            "wine_user_id" =>$data['wine_user']->id,
            "bottle_size" =>"L",
            "country_id"=> $data['country']->id,
            "region_id" => $data['region']->id,
            "producer_id" => $data['producer']->id,
            "wine_maker" =>"Wine maker",
            "type_of_wine" =>$data['type_of_wine']->id,
            "grape_varieties"=>"Wine maker",
            "wine_tag_id" =>$data['wine_tag']->id
        ]);

        $response->assertJsonValidationErrors(['year']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_grape_varieties_required_field_failed()
    {
        $data = $this->common_factory();
        $response = $this->actingAs($data['wine_user'])->post('/api/product', [
            "wine_name" => "Test wine",
            "drink_type_id" =>$data['drink_type']->id,
            "wine_user_id" =>$data['wine_user']->id,
            "bottle_size" =>"L",
            "country_id"=> $data['country']->id,
            "region_id" => $data['region']->id,
            "producer_id" => $data['producer']->id,
            "wine_maker" =>"Wine maker",
            "type_of_wine" =>$data['type_of_wine']->id,
            "year" =>"2022",
            "wine_tag_id" =>$data['wine_tag']->id
        ]);

        $response->assertJsonValidationErrors(['grape_varieties']);
        $response->assertStatus(422);
    }

    public function test_store_product_without_wine_tag_required_field_failed()
    {
        $data = $this->common_factory();
        $response = $this->actingAs($data['wine_user'])->post('/api/product', [
            "wine_name" => "Test wine",
            "drink_type_id" =>$data['drink_type']->id,
            "wine_user_id" =>$data['wine_user']->id,
            "bottle_size" =>"L",
            "country_id"=> $data['country']->id,
            "region_id" => $data['region']->id,
            "producer_id" => $data['producer']->id,
            "wine_maker" =>"Wine maker",
            "type_of_wine" =>$data['type_of_wine']->id,
            "year" =>"2022",
            "grape_varieties"=>"Wine maker",
        ]);

        $response->assertJsonValidationErrors(['wine_tag_id']);
        $response->assertStatus(422);
    }


}
