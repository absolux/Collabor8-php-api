<?php

return [

    /*
    |--------------------------------------------------------------------------
    | JWT Authentication Secret Key
    |--------------------------------------------------------------------------
    |
    | it will be used to sign the tokens.
    |
    */

    'key' => env('JWT_KEY', env('APP_KEY')),
    
    /*
    |--------------------------------------------------------------------------
    | JWT Authentication Header
    |--------------------------------------------------------------------------
    |
    | it will be used to parse token from request, or send it into the response
    |
    */

    'header' => 'X-Auth-Token',

    /*
    |--------------------------------------------------------------------------
    | JWT time to live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in seconds) that the token will be valid for.
    | Defaults to 12 hours
    |
    */

    'ttl' => 3600 * 12,

    /*
    |--------------------------------------------------------------------------
    | JWT hashing algorithm
    |--------------------------------------------------------------------------
    |
    | Specify the hashing algorithm that will be used to sign the token.
    | Not yet implemented
    |
    */

    'algo' => 'HS256',
    
    /*
    |--------------------------------------------------------------------------
    | JWT public claims
    |--------------------------------------------------------------------------
    |
    | put here your public claims to add to the token
    | Not yet implemented
    |
    */
    'claims' => [],

    
];