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
		"with" => ["country", "owner"],
		"list_attrs" => [
			"name",
			"slug",
			"owner.id" => "user_id",
			"owner.username" => "username",
			"country.name" => "country",
			"country.id" => "country_id",
		]
	],
	"producer" => [
		"model" => Producer::class,
		"with" => ["country", "owner"],
		"list_attrs" => [
			"name",
			"slug",
			"owner.id" => "user_id",
			"owner.username" => "username",
			"country.name" => "country",
			"country.id" => "country_id",
		]
	]
];
