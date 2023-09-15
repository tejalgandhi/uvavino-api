<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Auction;
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
use App\Models\Wishlist;
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
        $wine_user = WineUser::latest()->first();

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
        WineTag::factory()->count(10)->create();
        WineVariety::factory()->count(10)->create();
        $wine_tags = WineTag::pluck('id')->toArray();
        $wine_variety = WineVariety::pluck('id')->toArray();
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
            "wine_type_id" => $wine_type->id,
            "review_name" => "sffsdf",
            "basket_quantity" => "1",
            "unit_quantity" => "1",
            "unit" => "1",
            "wine_user_id"=>$wine_user->id,
            'wine_variety' => implode(',',$wine_variety),
            'wine_tag' => implode(',',$wine_tags),
        ]);

        //create auction data
        \App\Models\Basket::factory(1)->create();

        $basket = Basket::latest()->first();
        \App\Models\Product::factory(10)->create([
            "basket_id" => $basket->id,
           ]);
        \App\Models\Auction::factory(10)->create([
            "basket_id"=>$basket->id,
            "created_by"=>$wine_user->id,
        ]);
        $auction = Auction::latest()->first();
        \App\Models\Wishlist::factory(1)->create([
            'wine_user_id' => $wine_user->id,
            'ref_id' => $auction->id,
            'type' => Wishlist::AUCTION_TYPE
        ]);

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
            "wine_type_id" => $wine_type->id,
            "review_name" => "sffsdf",
            "basket_quantity" => "1",
            "unit_quantity" => "1",
            "unit" => "1",
            "wine_user_id"=>$wine_user->id,
            'wine_variety' => implode(',',$wine_variety),
            'wine_tag' => implode(',',$wine_tags),
        ]);
        \App\Models\Auction::factory(10)->create(["basket_id"=>$basket->id]);

    }
}
