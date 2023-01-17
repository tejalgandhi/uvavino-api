<?php
return [
  "product_count" => 4,

  "select" => ["*"],

  "attributes" => [

    // Numberic values to compare.
    "drink_type_id" => ["score" => 1],
    "country_id" => ["score" => 1],
    "region_id" => ["score" => 1],
    "producer_id" => ["score" => 1],
    "type_of_wine" => ["score" => 1],
    "year" => ["score" => 1],
    "wine_tag_id" => ["score" => 1],
    "score" => ["score" => 1],

    // String values to compare.
    "wine_maker" => ["score" => 1, "template" => 'strcmp'],
    "grape_varieties" => ["score" => 1, "template" => 'strcmp'],
    "medal" => ["score" => 1, "template" => 'strcmp'],
    "award_name" => ["score" => 1, "template" => 'strcmp'],

  ],
  
  "templates" => [

    "default" => "IF(`:attribute` = :expected, :score, 0)",
    "strcmp" => "IF(STRCMP(`:attribute`,':expected'), :score, 0)",

  ],

];