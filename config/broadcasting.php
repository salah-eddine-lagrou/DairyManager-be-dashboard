<?php

return [
    'default' => env('BROADCAST_DRIVER', 'null'),

    'connections' => [
        'pusher' => [
            'driver' => 'pusher',
            'key' => env('84c83ae250f4961e7a70'),
            'secret' => env('8d41de5676ed50acc870'),
            'app_id' => env('1876452'),
            'options' => [
                'cluster' => env('eu'),
                'useTLS' => true,
            ],
        ],

        'null' => [
            'driver' => 'null',
        ],

        // Additional connections...
    ],
];
