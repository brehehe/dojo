<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your CORS settings. These values control which
    | origins, methods, and headers are allowed for cross-origin requests.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    | Define the paths that CORS headers should be applied to. Use '*' to
    | match all paths, or specify an array of specific URI patterns.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    |
    | The HTTP methods that are allowed for cross-origin requests.
    |
    */

    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | The origins that are allowed to make cross-origin requests. For
    | production, set SESSION_SECURE_COOKIE and configure this to your
    | actual domain(s). Use '*' only for development.
    |
    */

    'allowed_origins' => [env('CORS_ALLOWED_ORIGINS', '*')],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origin Patterns
    |--------------------------------------------------------------------------
    |
    | Patterns for allowed origins, supporting wildcards.
    |
    */

    'allowed_origins_patterns' => [],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    |
    | The headers that are allowed in cross-origin requests.
    |
    */

    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    |
    | Headers that the browser is allowed to access from the response.
    |
    */

    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    |
    | The number of seconds the browser should cache the preflight response.
    |
    */

    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    |
    | Indicates whether the request can include user credentials like cookies,
    | HTTP authentication headers, or TLS client certificates. This must be
    | true for Inertia.js / Svelte frontends that rely on session cookies.
    |
    */

    'supports_credentials' => true,

];
