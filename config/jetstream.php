<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Jetstream Stack
    |--------------------------------------------------------------------------
    |
    | This configuration value informs Jetstream which "stack" you will be
    | using for your application. In general, this value is set for you
    | during installation and will not need to be changed after that.
    |
    | Supported: "livewire", "inertia"
    |
    */

    'stack' => env('JETSTREAM_STACK', 'livewire'),

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Some of Jetstream's features are optional. You may disable the features
    | by removing them from this array. You can use this array to enable
    | or disable Jetstream's features on a per user basis as required.
    |
    */

    'features' => [
        'account_invitation',
        'api',
        'teams',
    ],

    /*
    |--------------------------------------------------------------------------
    | Profile Photo Disk
    |--------------------------------------------------------------------------
    |
    | This configuration value determines the default disk that will be used
    | when storing profile photos for your application's users. Typically
    | this will be the "public" disk but you may adjust this if needed.
    |
    */

    'profile_photo_disk' => 'public',

    /*
    |--------------------------------------------------------------------------
    | Profile Photo Maximum File Size
    |--------------------------------------------------------------------------
    |
    | This configuration value determines the maximum file size in kilobytes
    | that will be accepted when uploading a user profile photo. If the photo
    | exceeds this size, an error response will be generated for the user.
    |
    */

    'profile_photo_max_size' => 1024,

    /*
    |--------------------------------------------------------------------------
    | Terms of Service URL
    |--------------------------------------------------------------------------
    |
    | This value is the URL of your application's terms of service. This value
    | is used when building the links to your application's terms of service
    | in your application's registration view. You should change this value
    | to match the URL of your application's actual terms of service page.
    |
    */

    'terms_of_service_url' => '',

    /*
    |--------------------------------------------------------------------------
    | Privacy Policy URL
    |--------------------------------------------------------------------------
    |
    | This value is the URL of your application's privacy policy. This value
    | is used when building the links to your application's privacy policy
    | in your application's registration view. You should change this value
    | to match the URL of your application's actual privacy policy page.
    |
    */

    'privacy_policy_url' => '',

];
