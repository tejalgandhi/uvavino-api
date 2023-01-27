<?php

namespace Tests\Feature;

use App\Models\Country;
use App\Models\DrinkType;
use App\Models\Producer;
use App\Models\Product;
use App\Models\Region;
use App\Models\Review;
use App\Models\User;
use App\Models\WineTag;
use App\Models\WineType;
use App\Models\WineUser;
use Tests\TestCase;

class ReviewTest extends TestCase
{

    private function check_structure($response) {
        $response->assertJsonStructure([
            "meta"=>["current_page","last_page","from","to","total","per_page"],
            "data" => ["*" => ["id",
                "ref_type",
                "ref_id",
                "user_id",
                "rating",
                "review_content",
            ]]
        ]);
    }

    public function common_factory(){
        $c = rand(1, 15);
        Product::factory($c)->create();
        User::factory($c)->create();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */

    public function test_simple_review_listing()
    {
        $this->common_factory();
        $product = Product::latest()->first();
        $user = User::latest()->first();

        $response = $this->get('/api/reviews');
        $response->assertJsonCount(0, 'data');
        $response->assertStatus(200);

        $c = rand(15, 30);
        Review::factory()->count($c)->create(['ref_type' => 'product','ref_id'=>$product->id,'user_id'=>$user->id]);

        $response = $this->get('/api/reviews');
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $response = $this->get('/api/reviews?ref_type=product&ref_id='.$product->id);
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $response = $this->get('/api/reviews?ref_type=product&ref_id=1000');
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);
    }

    public function test_store_review(){
        $data = $this->store_review_array();
        $response =  $this->post('/api/reviews', $data['review']);
        $response->assertStatus(200);


        $data = $this->store_review_array('ref_type');
        $response =  $this->post('/api/reviews', $data['review']);
        $response->assertJsonValidationErrors(['ref_type']);
        $response->assertStatus(422);

        $data = $this->store_review_array('ref_id');
        $response =  $this->post('/api/reviews', $data['review']);
        $response->assertJsonValidationErrors(['ref_id']);
        $response->assertStatus(422);

        $data = $this->store_review_array('user_id');
        $response =  $this->post('/api/reviews', $data['review']);
        $response->assertJsonValidationErrors(['user_id']);
        $response->assertStatus(422);

        $data = $this->store_review_array('rating');
        $response =  $this->post('/api/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);

        $data = $this->store_review_array('rating',15);
        $response =  $this->post('/api/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);

        $data = $this->store_review_array('rating',0);
        $response =  $this->post('/api/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);
    }
    public function store_review_array($params = null ,$param_value = null){
        $data = $this->common_factory();
        $c =rand(1,10);
        $product = Product::latest()->first();
        $user = User::latest()->first();
        $review = [
            "ref_type"=>"product",
            "ref_id"=>$product->id,
            "user_id"=>$user->id,
            "rating"=>$c,
            "review_content"=>'Test rating',
        ];

        if(!empty($params) && empty($param_value)){
            unset($review[$params]);
        }


        if(!empty($params) && !empty($param_value)){
            $review[$params] = $param_value;
        }
        return compact('review','data');
    }


}
