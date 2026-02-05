<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Birbank Business B2B API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Birbank Business (Kapital) B2B API integration.
    | Supports both test and production environments.
    |
    */

    'base_url_prod' => env('BIRBANK_BASE_URL_PROD', 'https://my.birbank.business'),
    'base_url_test' => env('BIRBANK_BASE_URL_TEST', 'https://pre-my.birbank.business'),

    'default_env' => env('BIRBANK_ENV', 'test'), // 'test' or 'prod'

    'timeout' => env('BIRBANK_TIMEOUT', 30), // Request timeout in seconds
    'connect_timeout' => env('BIRBANK_CONNECT_TIMEOUT', 10), // Connection timeout in seconds

    'verify_ssl' => env('BIRBANK_VERIFY_SSL', true), // Verify SSL certificates

    /*
    |--------------------------------------------------------------------------
    | API Endpoints
    |--------------------------------------------------------------------------
    */
    'endpoints' => [
        'login' => '/api/b2b/login',
        'refresh' => '/api/b2b/refresh', // Not implemented yet
        'accounts' => '/api/b2b/accounts', // Not implemented yet
        'account_statement' => '/api/b2b/account-statement', // Not implemented yet
    ],
];

