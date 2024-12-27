<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for Midtrans payment gateway.
    | You can set the API keys for your Midtrans account here.
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'environment' => env('MIDTRANS_ENVIRONMENT', 'sandbox'), // 'sandbox' for testing, 'production' for live transaction
];
