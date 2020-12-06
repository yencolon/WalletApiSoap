<?php

use App\Http\Controllers\AuthController;
use App\Types\LoginUser;
// use App\Models\User;
// use App\Types\RegisterUser;

return [
    'services'          => [
        'auth'              => [
            'name'              => 'Auth',
            'class'             => AuthController::class,
            'exceptions'        => [
                'Exception'
            ],
            'types'             => [
                'user'          => User::class,
                'loginUser'     => LoginUser::class,
                'registerUser'  => RegisterUser::class
            ],
            'strategy'          => 'ArrayOfTypeComplex',
            'headers'           => [
                'Cache-Control'     => 'no-cache, no-store',
                'user_agent' => 'PHPSoapClient'
            ],
            'options'           => []
        ]
    ],
];
