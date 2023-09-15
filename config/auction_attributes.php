<?php

use App\Enums\BottleSize;
use App\Services\Filters\BrandFilter;
use App\Services\Filters\CountryFilter;
use App\Services\Filters\DrinkTypeFilter;
use App\Services\Filters\ProducerFilter;
use App\Services\Filters\RegionFilter;
use App\Services\Filters\WineTagFilter;
use App\Services\Filters\WineTypeFilter;
use App\Services\Filters\WineVerietyFilter;
use App\Services\Filters\StaticFilter\BottleSizeFilter;
use App\Services\Filters\StaticFilter\YearFilter;
use App\Services\Filters\StaticFilter\AuctionTypeFilter;
use App\Services\Filters\StaticFilter\MedalFilter;
use App\Services\Filters\StaticFilter\CapacityFilter;

return [
    "price" => [
        '1',
        '1000',
    ],
    "bottle_size" => BottleSizeFilter::class,
    "capacity" => CapacityFilter::class,
    "type" => AuctionTypeFilter::class,
    "year" => YearFilter::class,
    "medal" =>MedalFilter::class,
    "producer" => ProducerFilter::class,
    "wine-type" => WineTypeFilter::class,
    "country" => CountryFilter::class,
    "region" => RegionFilter::class,
    "brand" => BrandFilter::class,
	"drink-type" => DrinkTypeFilter::class,
	"wine-variety" => WineVerietyFilter::class,
	"wine-tag" => WineTagFilter::class,
];
