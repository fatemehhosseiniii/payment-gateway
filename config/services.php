<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'zarinpal' => [
        'driver' => env('ZARINPAL_DRIVER'), //sandbox OR payment
        'merchant-id' => env('MERCHABT_ID'),
        'callback-url' => env('ZARINPAL_CALLBACK'),

        'sandbox-request-url' => env('SANDBOX_ZARINPAL_REQUEST_URL'),
        'sandbox-pay-url' => env('SANDBOX_ZARINPAL_PAY_URL'),
        'sandbox-verify-url' => env('SANDBOX_ZARINPAL_VERIFY_URL'),

        'payment-request-url' => env('ZARINPAL_REQUEST_URL'),
        'payment-pay-url' => env('ZARINPAL_PAY_URL'),
        'payment-verify-url' => env('ZARINPAL_VERIFY_URL'),
    ]

];
