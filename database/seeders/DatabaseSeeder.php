<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Brand;
use App\Models\Country;
use App\Models\DrinkType;
use App\Models\Producer;
use App\Models\Region;
use App\Models\WineTag;
use App\Models\WineUser;
use App\Models\WineVariety;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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

        $country = Country::first();
        $wine_user = WineUser::first();

        \App\Models\Region::factory(10)->create(['country_id'=>$country->id]);
        \App\Models\Brand::factory(10)->create(['country_id'=>$country->id,'wine_user_id'=>$wine_user->id]);
        \App\Models\Producer::factory(10)->create(['country_id'=>$country->id,'wine_user_id'=>$wine_user->id]);


        $drink_type = DrinkType::first();
        $wine_tag = WineTag::first();
        $wine_variety = WineVariety::first();
        $region = Region::first();
        $producer = Producer::first();
        $brand = Brand::first();

//        \App\Models\Product::factory(10)->create([
//            'country_id'=>$country->id,
//            'wine_user_id'=>$wine_user->id,
//            'drink_type_id'=>$drink_type->id,
//            'wine_tag_id'=>$wine_tag->id,
//            'region_id'=>$region->id,
//            'producer_id'=>$producer->id,
//        ]);

    }
}
