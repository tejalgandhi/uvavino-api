<?php

return [
	"drink-type" => [
		"table" => 'drink_types',
		"list_attrs" => [
			"id",
			"name",
			"slug",
			"status",
			"is_approved"
		]
	],
	"wine-type" => [
		"table" => 'wine_types',
		"list_attrs" => [
			"id",
			"name",
			"slug",
			"status",
			"is_approved"
		]
	],
	"wine-variety" => [
		"table" => 'wine_varieties',
		"list_attrs" => [
			"id",
			"name",
			"slug",
			"status",
			"is_approved"
		]
	],
	"wine-tag" => [
		"table" => 'wine_tags',
		"list_attrs" => [
			"id",
			"name",
			"slug",
			"status",
			"is_approved"
		]
	],
	"country" => [
		"table" => 'countries',
		"list_attrs" => [
			"id",
			"name",
			"slug",
			"status"
		]
	],
	"region" => [
		"table" => 'regions',
		"list_attrs" => [
			"id",
			"country_id",
			"name",
			"slug",
			"status"
		]
	]
];
