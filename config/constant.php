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
    'date_format'=>'D, d M Y | h:i a '
];
