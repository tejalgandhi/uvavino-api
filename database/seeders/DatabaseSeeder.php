<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Basket;
use App\Models\Brand;
use App\Models\Country;
use App\Models\DrinkType;
use App\Models\Producer;
use App\Models\Region;
use App\Models\WineTag;
use App\Models\WineType;
use App\Models\WineUser;
use App\Models\WineVariety;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application"s database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(10)->create();
        \App\Models\WineUser::factory(10)->create();
        \App\Models\Country::factory(10)->create();
        \App\Models\Category::factory(10)->create();
        \App\Models\StaticPage::factory(10)->create();
        \App\Models\DrinkType::factory(10)->create();
        \App\Models\WineTag::factory(10)->create();
        \App\Models\WineVariety::factory(10)->create();
        \App\Models\WineType::factory(10)->create();
        \App\Models\Basket::factory(1)->create();
        \App\Models\AuctionPackage::factory(10)->create();

        $country = Country::first();
        $wine_user = WineUser::first();

        \App\Models\Region::factory(10)->create(["country_id"=>$country->id]);
        \App\Models\Brand::factory(10)->create(["country_id"=>$country->id,"wine_user_id"=>$wine_user->id]);
        \App\Models\Producer::factory(10)->create(["country_id"=>$country->id,"wine_user_id"=>$wine_user->id]);


        $drink_type = DrinkType::first();
        $wine_type = WineType::first();
        $wine_tag = WineTag::first();
        $wine_variety = WineVariety::first();
        $region = Region::first();
        $producer = Producer::first();
        $brand = Brand::first();
        $basket = Basket::first();
        \App\Models\Product::factory(10)->create([
            "title" => "TESTTTT",
            "description" => "TESTTTT",
            "wine_name" => "TESTTTT",
            "region_id" => $region->id,
            "basket_id" => $basket->id,
            "producer_id" => $producer->id,
            "year" => "2023",
            "wine_maker" => "TESTTT",
            "country_id" => $country->id,
            "bottle_size" => "L",
            "drink_type_id"=>$drink_type->id,
            "wine_tag_id"=>$wine_tag->id,
            "type_of_wine" => $wine_type->id,
            "grape_varieties" => "ddsfsdf",
            "review_name" => "sffsdf",
            "basket_quantity" => "1",
            "unit_quantity" => "1",
            "unit" => "1",
            "wine_user_id"=>$wine_user->id,

        ]);

        //create auction data
        \App\Models\Basket::factory(1)->create();

        $basket = Basket::latest()->first();
        \App\Models\Product::factory(10)->create([
            "basket_id" => $basket->id,
           ]);
        \App\Models\Auction::factory(10)->create(["basket_id"=>$basket->id]);


        //create auction data
        \App\Models\Basket::factory(1)->create();

        $basket = Basket::latest()->first();
        \App\Models\Product::factory(10)->create([
            "title" => "TESTTTT",
            "description" => "TESTTTT",
            "wine_name" => "TESTTTT",
            "region_id" => $region->id,
            "basket_id" => $basket->id,
            "producer_id" => $producer->id,
            "year" => "2023",
            "wine_maker" => "TESTTT",
            "country_id" => $country->id,
            "bottle_size" => "L",
            "drink_type_id"=>$drink_type->id,
            "wine_tag_id"=>$wine_tag->id,
            "type_of_wine" => $wine_type->id,
            "grape_varieties" => "ddsfsdf",
            "review_name" => "sffsdf",
            "basket_quantity" => "1",
            "unit_quantity" => "1",
            "unit" => "1",
            "wine_user_id"=>$wine_user->id,

        ]);
        \App\Models\Auction::factory(10)->create(["basket_id"=>$basket->id]);

    }
}
