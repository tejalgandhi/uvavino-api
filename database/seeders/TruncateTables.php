<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Auction;
use App\Models\AuctionBid;
use App\Models\AuctionInvite;
use App\Models\AutomaticBid;
use App\Models\Basket;
use App\Models\Brand;
use App\Models\Country;
use App\Models\DrinkType;
use App\Models\Producer;
use App\Models\Product;
use App\Models\Region;
use App\Models\WineTag;
use App\Models\WineType;
use App\Models\WineUser;
use App\Models\WineVariety;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TruncateTables extends Seeder
{
    /**
     * Seed the application"s database.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        AutomaticBid::query()->truncate();
        AutomaticBid::query()->truncate();
        AuctionBid::query()->truncate();
        AuctionInvite::query()->truncate();
        Auction::query()->truncate();
        Basket::query()->truncate();
        Product::query()->truncate();
        Schema::enableForeignKeyConstraints();

    }
}
