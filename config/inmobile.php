<?php

/**
 * Configuration file for Laravel InMobile.
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Basic settings
    |--------------------------------------------------------------------------
    |
    */
    'base_url' => 'https://api.inmobile.com/',
    'version' => 'v4',

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | API token or username authentication.
    |
    | In case of authentication with username and password, just provide
    | the username and password separated by ":".
    | Example: "myuser:password"
    |
    | In case of api key authentication just provide the key.
    */
    'api_key' => env('INMOBILE_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Request settings
    |--------------------------------------------------------------------------
    |
    */
    'timeout' => 30,   // Maximum request timeout in seconds
];
