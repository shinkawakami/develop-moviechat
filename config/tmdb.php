<?php

return [
    'api_key' => env('TMDB_API_KEY'),

    'tmdb' => [
        // v4 の Bearer 用（withToken で使う）
        'token' => env('TMDB_V4_TOKEN'),

        // v3 API Key 用（クエリの api_key に付ける）
        'key'   => env('TMDB_API_KEY'),
    ],
];