<?php

use App\Models\Brand;
use App\Models\Country;
use App\Models\DrinkType;
use App\Models\Producer;
use App\Models\Region;
use App\Models\WineTag;
use App\Models\WineType;
use App\Models\WineVariety;
use App\Services\Filters\BrandFilter;
use App\Services\Filters\CountryFilter;
use App\Services\Filters\DrinkTypeFilter;
use App\Services\Filters\ProducerFilter;
use App\Services\Filters\RegionFilter;
use App\Services\Filters\WineTagFilter;
use App\Services\Filters\WineTypeFilter;
use App\Services\Filters\WineVerietyFilter;

return [
    "bottle_size" => [
        '10ml',
        '20ml'
    ],
    "capacity" => [
        'ml',
        'liters',
    ],
    "price" => [
        '1',
        '1000',
    ],
    "auction_type" => [
        'live',
        'timebased',
    ],
    "medal" => [
        'Platinum',
        'Gold',
        'Silver',
    ],
    "producer" => ProducerFilter::class,
    "wine-type" => WineTypeFilter::class,
    "country" => CountryFilter::class,
    "region" => RegionFilter::class,
    "brand" => BrandFilter::class,
	"drink-type" => DrinkTypeFilter::class,
	"wine-variety" => WineVerietyFilter::class,
	"wine-tag" => WineTagFilter::class,
];
