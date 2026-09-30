<?php

return [

    'admin' => [
        'name' => env('DEMO_ADMIN_NAME', 'Lumière Admin'),
        'email' => env('DEMO_ADMIN_EMAIL'),
        'password' => env('DEMO_ADMIN_PASSWORD'),
    ],

    'customer' => [
        'name' => env('DEMO_CUSTOMER_NAME', 'Lumière Customer'),
        'email' => env('DEMO_CUSTOMER_EMAIL'),
        'password' => env('DEMO_CUSTOMER_PASSWORD'),
    ],

];