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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'firebase' => [
        'api_key' => env('API_KEY'), // Only used from JS integration
        'auth_domain' => env('AUTH_DOMAIN'), // Only used from JS integration
        'storage_bucket' => env('STORAGE_BUCKET'), // Only used from JS integration
        'project_id' => env('PROJECT_ID'),
        'messaging_sender_id' => env('MESSAGING_SENDER_ID'), // Only used from JS integration
        'app_id' => env('APP_ID'), // Only used from JS integration
        'measurement_id' => env('MEASUREMENT_ID'), // Only used from JS integration
    ],
];
