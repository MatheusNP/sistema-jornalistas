<?php

return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'journalists',
    ],
    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'journalists',
        ],
    ],
    'providers' => [
        'journalists' => [
            'driver' => 'eloquent',
            'model' => \App\Models\Journalist::class
        ]
    ]
];
