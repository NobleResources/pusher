<?php

return [
    'user_model' => VanLonden\Pusher\Models\User::class,

    'tables' => [
        'users' => 'users',

        'past_push' => 'past_push',
        'past_push_errors' => 'past_push_errors',

        'future_push' => 'future_push',
        'future_push_user' => 'future_push_user',
    ],

    'batch_size' => 1000,

    'server_key' => env('FIREBASE_SERVER_KEY'),
];