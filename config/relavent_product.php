<?php
return [
  "product_count" => 4,

  "select" => ["*"],

  "attributes" => [

    // Numberic values to compare.
    "country_id" => ["score" => 1],
    "region_id" => ["score" => 1],
    "producer_id" => ["score" => 1],
    "year" => ["score" => 1],

    // String values to compare.
    "wine_maker" => ["score" => 1, "template" => 'strcmp'],


  ],

  "templates" => [

    "default" => "IF(`:attribute` = :expected, :score, 0)",
    "strcmp" => "IF(`:attribute` = ':expected', :score, 0)",

  ],

];
