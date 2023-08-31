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
    'front_app_url' => env('FRONT_END_URL')??'http://165.22.72.229/',
    'upload_url' => env('UPLOAD_URL') ?? 'https://dev.admin.uvavino.immera.io/uploads/',
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

];
