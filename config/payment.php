<?php

return [
    'stripe' => [
        'secret_key' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'country' => env('STRIPE_COUNTRY', 'FR'),
    ],
    'paypal' => [
        'url' => env('PAYPAL_URL', "https://api-m.sandbox.paypal.com/"),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret_key' => env('PAYPAL_CLIENT_SECRET'),
    ],
    'payment_methods' => [
        'default' => ['bank_transfer', 'card', 'paypal']
    ],
    'route_prefix' => env('PAYMENT_ROUTE_PREFIX', "/api"),
];
