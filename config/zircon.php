<?php

return [
    'LC_API_URL' => env('LC_API_URL'),
    'LC_API_TOKEN' => env('LC_API_TOKEN'),
    'LC_API_USER_AGENT' => env('LC_API_USER_AGENT'),
    'GAME_PROVIDER_NAME' => env('GAME_PROVIDER_NAME'),

    'FUNKY_API_URL' => env('FUNKY_API_URL'),
    'FUNKY_API_TOKEN' => env('FUNKY_API_TOKEN'),
    'FUNKY_API_USER_AGENT' => env('FUNKY_API_USER_AGENT'),
    'FUNKY_ZIRCON_TOKEN' => env('FUNKY_ZIRCON_TOKEN'),
    'FUNKY_ZIRCON_USER_AGENT' => env('FUNKY_ZIRCON_USER_AGENT'),

    'SBO_ASI_API_URL' => env('SBO_ASI_API_URL'),
    'SBO_LC_ASI_API_URL' => env('SBO_LC_ASI_API_URL'),

    'EYECON_ACCESS_ID' => env('EYECON_ACCESS_ID'),
    'EYECON_API_URL' => env('EYECON_API_URL'),
    'EYECON_AUTHORIZATION_TOKEN' => env('EYECON_AUTHORIZATION_TOKEN'),

    'PS_API_URL' => env('PS_API_URL'),

    'ZIRCON_REDIS_DB' => env('ZIRCON_REDIS_DB'),

    'ENV_ID' => env('ENV_ID'),

    'APP_VERSION' => env('APP_VERSION', 'app version not set'),
    'APP_NAME' => env('APP_NAME', 'app name not set'),

    'ZIRCON_ASI_SERVER_ID' => env('ZIRCON_ASI_SERVER_ID')
];