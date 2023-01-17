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
    public function test_cellar_listing()
    {
        $response = $this->get('/api/cellar');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(10, 30);
        Product::factory()->count($c)->create();

        $response = $this->get('/api/cellar');
        $response->assertJsonCount(10, 'data');
        $response->assertJsonStructure([
            "meta"=>["current_page","last_page","from","to","total","per_page"],
            "data" => ["*" => ["id", "wine_name", "description", "wine_user", "drink_type","bottle_size",
                "country_name","region_name","producer_name","wine_maker","wine_type","year","grape_varieties",
                "wine_tag","storage_condition","review_and_rewards","medal","award_name","score","review_name"]]
        ]);
        $response->assertStatus(200);
    }

    public function test_get_single_cellar()
    {
        $page = Product::factory()->create();
        $response = $this->get('/api/cellar/' . $page->id);
        $response->assertJson($page->toArray());
        $response->assertJsonStructure(["id", "wine_name", "description", "wine_user_id", "drink_type_id","bottle_size",
            "country_id","region_id","producer_id","wine_maker","type_of_wine","year","grape_varieties",
            "wine_tag_id","storage_condition","review_and_rewards","medal","award_name","score","review_name"]);
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

    public function test_simple_store_cellar()
    {
        $data = $this->common_factory();
        $response = $this->post('/api/cellar', [
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

    public function test_store_cellar_without_required_field_failed()
    {
        $data = $this->common_factory();
        $response = $this->post('/api/cellar', [
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


}
