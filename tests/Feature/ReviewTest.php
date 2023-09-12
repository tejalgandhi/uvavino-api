<?php

namespace Tests\Feature;


use App\Models\Auction;
use App\Models\AuctionPackage;
use App\Models\Product;
use App\Models\Review;
use App\Models\WineUser as User;
use Tests\TestCase;

class ReviewTest extends TestCase
{

    private function check_structure($response) {
        $response->assertJsonStructure([
            "meta"=>["current_page","last_page","from","to","total","per_page"],
            "data" => ["*" => ["id",
                "reviewable_type",
                "reviewable_id",
                "wine_user_id",
                "rating",
                "review_content",
            ]]
        ]);
    }

    public function common_factory(){
        $c = rand(1, 15);
        Product::factory($c)->create();
        Auction::factory($c)->create();
        AuctionPackage::factory($c)->create();
        User::factory($c)->create();
        $product = Product::latest()->first();
        $auction = Auction::latest()->first();
        $auction_package = AuctionPackage::latest()->first();
        $wine_user = User::latest()->first();

        return compact('wine_user','product','auction','auction_package');
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

        Review::factory()->count($c)->create(['reviewable_type' => Product::class,'reviewable_id'=>$product->id,'wine_user_id'=>$user->id]);

        $response = $this->get('/api/reviews');
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $response = $this->get('/api/reviews?reviewable_type=product&reviewable_id='.$product->id);
        $response->assertJsonCount(10, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);

        $response = $this->get('/api/reviews?reviewable_type=product&reviewable_id=1000');
        $response->assertJsonCount(0, 'data');
        $this->check_structure($response);
        $response->assertStatus(200);
    }

    public function test_store_review(){
        $data = $this->store_review_array();
        $this->actingAs($data['data']['wine_user']);
        $response =  $this->post('/api/product/'. $data['data']['product']->id.'/reviews', $data['review']);
        $response->assertStatus(200);

        $data = $this->store_review_array('rating');
        $response =  $this->post('/api/product/'.$data['data']['product']->id.'/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);

        $data = $this->store_review_array('rating',15);
        $response =  $this->post('/api/product/'.$data['data']['product']->id.'/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);

        $data = $this->store_review_array('rating',0);
        $response =  $this->post('/api/product/'.$data['data']['product']->id.'/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);

        $data = $this->store_review_array();
        $response =  $this->post('/api/auction/'.$data['data']['auction']->id.'/reviews', $data['review']);
        $response->assertStatus(200);

        $data = $this->store_review_array('rating');
        $response =  $this->post('/api/auction/'.$data['data']['auction']->id.'/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);

        $data = $this->store_review_array('rating',15);
        $response =  $this->post('/api/auction/'.$data['data']['auction']->id.'/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);

        $data = $this->store_review_array('rating',0);
        $response =  $this->post('/api/auction/'.$data['data']['auction']->id.'/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);

        $data = $this->store_review_array();
        $response =  $this->post('/api/auction_package/'.$data['data']['auction_package']->id.'/reviews', $data['review']);
        $response->assertStatus(200);

        $data = $this->store_review_array('rating');
        $response =  $this->post('/api/auction_package/'.$data['data']['auction_package']->id.'/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);

        $data = $this->store_review_array('rating',15);
        $response =  $this->post('/api/auction_package/'.$data['data']['auction_package']->id.'/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);

        $data = $this->store_review_array('rating',0);
        $response =  $this->post('/api/auction_package/'.$data['data']['auction_package']->id.'/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);

        $data = $this->store_review_array();
        $response =  $this->post('/api/wine_user/'.$data['data']['wine_user']->id.'/reviews', $data['review']);
        $response->assertStatus(200);

        $data = $this->store_review_array('rating');
        $response =  $this->post('/api/wine_user/'.$data['data']['wine_user']->id.'/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);

        $data = $this->store_review_array('rating',15);
        $response =  $this->post('/api/wine_user/'.$data['data']['wine_user']->id.'/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);

        $data = $this->store_review_array('rating',0);
        $response =  $this->post('/api/wine_user/'.$data['data']['wine_user']->id.'/reviews', $data['review']);
        $response->assertJsonValidationErrors(['rating']);
        $response->assertStatus(422);

    }

    public function store_review_array($params = null ,$param_value = null){
        $data = $this->common_factory();
        $c =rand(1,10);
        $review = [
            "rating"=>$c,
            "review_content"=>fake()->text(35),
        ];

        if(!empty($params) && empty($param_value)){
            unset($review[$params]);
        }


        if(!empty($params) && !empty($param_value)){
            $review[$params] = $param_value;
        }
        return compact('review','data');
    }

    public function test_get_product_reviews()
    {
        $data = $this->store_review_array();
        $this->actingAs($data['data']['wine_user']);
        $response =  $this->post('/api/product/'. $data['data']['product']->id.'/reviews', $data['review']);
        $response->assertStatus(200);

        $data = $this->store_review_array();
        $this->actingAs($data['data']['wine_user']);
        $response =  $this->post('/api/product/'. $data['data']['product']->id.'/reviews', $data['review']);
        $response->assertStatus(200);

        $response =  $this->get('/api/product/'.$data['data']['product']->id.'/reviews', $data['review']);
        $response->assertStatus(200);
    }


}
