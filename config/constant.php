<?php
// Don't Modify this file, As this is the gema digital package file That used in backend
return [
    'general' => [
        'boolean' => [
            1 => 'yes',
            0 => 'no',
        ],
        'check' => [
            1 => 'active',
            0 => 'inactive',
        ],
        'publish' => [
            'publish' => 'publish',
            'draft' => 'draft',
        ],
    ],

    'user' => [
        'status' => [
            1 => 'active',
            0 => 'inactive',
        ],
        'roles' => [
            1 => 'admin',
            2 => 'editor',
            3 => 'member',
        ],
        'permissions' => [],
    ],
    'front_app_url' => env('FRONT_END_URL')??'',
    'upload_url' => env('UPLOAD_URL') ?? '',
    'bottle_size'=>[
        '10ml'=>'10ml',
        '20ml'=>'20ml'
    ],
    'unit'=>[
        'ml'=>'ml',
        'liters'=>'liters',
    ],
    'auction_type'=>[
        '0'=>'live',
        '1'=>'timebased',
    ],
    'price'=>[
        'min_value'=>'1',
        'max_value'=>'1000',
    ],

    'date_format'=>'D, d M Y | h:i a ',
    'recommended_items_per_page'=>4,
    'new_items_per_page'=>4,
    'number_of_items_per_page'=>10,
    'configured_auction_time'=>'60',
    'configured_timebased_auction_time'=>'1',
    'minimum_bid_step'=>5,
];
