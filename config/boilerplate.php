<?php

return [

    'sign_up' => [

        'release_token' => env('SIGN_UP_RELEASE_TOKEN', false),

        'validation_rules' => [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]
    ],

    'login' => [

        'validation_rules' => [
            'email' => 'required|email',
            'password' => 'required'
        ]
    ],

    'finance-account' => [

        'validation_rules' => [
            'name' => 'required|string',
        ]
    ],

];
