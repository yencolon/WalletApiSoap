<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\WalletController;
use App\Models\Wallet;
use App\Models\WalletRecord;
use App\Utils\CommonResponse;

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
                'commonResponse' => CommonResponse::class
            ],
            'strategy'          => 'ArrayOfTypeComplex',
            'headers'           => [
                'Cache-Control'     => 'no-cache, no-store',
            ],
            'options'           => []
        ],
        'wallet'              => [
            'name'              => 'Wallet',
            'class'             =>  WalletController::class,
            'exceptions'        => [
                'Exception'
            ],
            'types'             => [
                'wallet'        => Wallet::class,
                'record'        => WalletRecord::class,
                'commonResponse' => CommonResponse::class
            ],
            'strategy'          => 'ArrayOfTypeComplex',
            'headers'           => [
                'Cache-Control'     => 'no-cache, no-store',
            ],
            'options'           => []
        ]
    ],
];
