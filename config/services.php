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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => '795786888261296', //Facebook API //765016374194622
        'client_secret' => '998a0523551b21856890b3e6913ecb60', //Facebook Secret //0abcefad36e37764bb018bd440cf3ccf
        'redirect' => 'https://www.dadreeios.com/login/facebook/callback',
    ],

    'google' => [
        'client_id' => '429162776431-o99p4ginn3v6si9b3uqpq3s4lpkk7467.apps.googleusercontent.com', //Google API
        'client_secret' => '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', //Google Secret
        'redirect' => 'http://www.dadreeios.com/login/google/callback',
    ],

];
