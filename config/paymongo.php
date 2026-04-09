<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PayMongo API Keys
    |--------------------------------------------------------------------------
    |
    | Your PayMongo public and secret keys. Use test keys (pk_test_ / sk_test_)
    | during development and switch to live keys in production.
    |
    | Test card: 4343434343434345, any future expiry, any 3-digit CVV
    | Test GCash: PayMongo sandbox auto-approves in test mode
    |
    */

    'public_key' => env('PAYMONGO_PUBLIC_KEY', ''),

    'secret_key' => env('PAYMONGO_SECRET_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | Webhook Secret
    |--------------------------------------------------------------------------
    |
    | Used to verify that incoming webhook requests are genuinely from PayMongo.
    | Set this to the webhook secret from your PayMongo dashboard.
    | Never hardcode this value — always read from environment.
    |
    */

    'webhook_secret' => env('PAYMONGO_WEBHOOK_SECRET', ''),
];
