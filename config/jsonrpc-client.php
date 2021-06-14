<?php

use Illuminate\Support\Str;

return [
    'clientName'  => 'site',
    'default'     => 'api',
    'connections' => [
        'api' => [
            'url'             => env('JSONRPC_URL_SERVICE_BALANCE', 'http://10.0.0.1:8001/api/jsonrpc'),
            'clientClass'     => '\\App\\Services\\BalanceApi',
            'extendedStubs'   => false,
            'middleware'      => [
                \Tochka\JsonRpcClient\Middleware\AuthTokenMiddleware::class => [
                    'name'  => 'X-Access-Key',
                    'value' => env('JSONRPC_KEY_SERVICE_BALANCE', Str::uuid()->toString()),
                ],
            ],
            'namedParameters' => true,
        ],
    ],
];
