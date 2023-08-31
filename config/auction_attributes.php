<?php

use App\Models\Brand;
use App\Models\Country;
use App\Models\DrinkType;
use App\Models\Producer;
use App\Models\Region;
use App\Models\WineTag;
use App\Models\WineType;
use App\Models\WineVariety;

return [
    "bottle_size" => [
        "data"=> [
            '0'=>'10ml',
            '1'=>'20ml'
        ]
    ],
    "capacity" => [
        "data"=> [
            '0'=>'ml',
            '1'=>'liters',
        ]
    ],
    "price" => [
        "data"=> [
            '0'=>'1',
            '1'=>'1000',
        ],
    ],
    "auction_type" => [
        "data"=> [
            '0'=>'live',
            '1'=>'timebased',
        ]
    ],
    "producer" => [
        "model" => Producer::class,
        "list_attrs" => [
            "id",
            "name",
            "slug",
            "status",
            "is_approved"
        ]
    ],
    "wine-type" => [
        "model" => WineType::class,
        "list_attrs" => [
            "id",
            "name",
            "slug",
            "status",
            "is_approved"
        ]
    ],
    "country" => [
        "model" => Country::class,
        "list_attrs" => [
            "id",
            "name",
            "slug",
            "status"
        ]
    ],
    "region" => [
        "model" => Region::class,
        "list_attrs" => [
            "id",
            "country_id",
            "name",
            "slug",
            "status"
        ]
    ],
    "brand" => [
        "model" => Brand::class,
        "list_attrs" => [
            "id",
            "name",
            "slug",
            "is_approved",
        ]
    ],

	"drink-type" => [
		"model" => DrinkType::class,
		"list_attrs" => [
			"id",
			"name",
			"slug",
			"status",
			"is_approved"
		]
	],
	"wine-variety" => [
		"model" => WineVariety::class,
		"list_attrs" => [
			"id",
			"name",
			"slug",
			"status",
			"is_approved"
		]
	],
	"wine-tag" => [
		"model" => WineTag::class,
		"list_attrs" => [
			"id",
			"name",
			"slug",
			"status",
			"is_approved"
		]
	],
];
