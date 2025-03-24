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
         
      
    // 'client_id' => '1097279568595198',
    // 'client_secret' =>'0c29d5b6952b0fcd6f69dbbe99c95aeb',
    
    'client_id' => '6058802037574834',
    'client_secret' =>'60fe2bc3779e9f170628b1bafe7cc686',
    'redirect' => 'https://walkofweb.in/facebook/callback',
    
  ], 
  'instagram' => [
         
    
    'client_id' => '400361359804168',
    'client_secret' =>'51f8e5e28591b8598a9fc33040a2fc16',
    'redirect' => 'https://walkofweb.in/ins/callback',
    'scopes' => ['public_profile','instagram_basic','instagram_manage_insights'],
    
  ], 
  'linkedin-openid' => [
      'client_id' => '770u34mn5vnto3',
    'client_secret' =>'WPL_AP1.vKN2jvl4tbCCgAcV.2AUdwg==',
    'redirect' => 'https://walkofweb.in/linkedin/callback'
    
    
  ], 
  'google' => [
    'client_id' => '1071709008835-cd3fv0ffv378a0oejiis5cr8jcrmphq4.apps.googleusercontent.com',
  'client_secret' =>'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',
  'redirect' => 'https://walkofweb.in/auth/google/callback'
  
  
], 
'youtube' => [
    'api_key' => 'AIzaSyAb3zrK5yFv9ePxZ_2vciY86Is0F3pB9HA',
],
'stripe' => [
        'key' => 'pk_test_51IUxQhHCUbph94h9lQGkPs5a7V3iDHDlJy2GBUvzXKcqVC1kS4Vc6R87zGWynEMHGaB0FklHROSv5bsrd1HZS54T00Fkj6dSdx',
        'secret' => 'sk_test_51IUxQhHCUbph94h9Qm5WHrLOK314ZEopEzdPIDsnc9Au51fsc0hhoqM5CuPgAR4EIWu90WW7XInQKwQ71OflMPCF003M9k81WT',
    ],
 
 
];
