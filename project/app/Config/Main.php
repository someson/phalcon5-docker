<?php

return [
    'debug' => env('APP_DEBUG'),
    'app' => [
        'locale'    => 'de_DE',
        'domain'    => env('APP_DOMAIN'),
        'timezone'  => 'Europe/Berlin',
        'baseUri'   => '/',
        'staticUri' => sprintf('https://%s/', env('APP_DOMAIN')),
    ],
    'database' => [
        'web' => [
            'adapter' => 'mysql',
            'options' => [
                'host'     => env('MYSQL_HOST'),
                'port'     => env('MYSQL_PORT', 3306),
                'username' => env('MYSQL_USERNAME'),
                'password' => env('MYSQL_PASSWORD'),
                'dbname'   => env('MYSQL_DATABASE'),
                'charset'  => env('MYSQL_CHARSET'),
            ],
        ],
        'cli' => [
            'adapter' => 'mysql',
            'options' => [
                'host'     => env('CLI_MYSQL_HOST'),
                'username' => env('CLI_MYSQL_USERNAME'),
                'password' => env('CLI_MYSQL_PASSWORD'),
                'dbname'   => env('CLI_MYSQL_DATABASE'),
                'port'     => env('CLI_MYSQL_PORT', 3306),
                'charset'  => env('CLI_MYSQL_CHARSET'),
            ],
        ],
        'metaDataCache' => [
            'adapter' => 'apcu',
            'options' => [
                'prefix' => CURRENT_APP . '-',
            ],
        ]
    ],
    'cache' => [
        'dataCache' => [
            'adapter' => 'apcu',
            'options' => [
                'prefix' => CURRENT_APP . '-',
            ],
        ],
        'modelsCache' => [
            'adapter' => 'apcu',
            'options' => [
                'prefix' => CURRENT_APP . '-',
            ],
        ],
    ],
    'auth' => [
        'identityName' => env('AUTH_IDENTITY'),
    ],
    'crypt' => [
        'salt' => env('CRYPT_SALT'),
        'key'  => env('CRYPT_KEY'),
    ],
    'cli' => [
        'reports' => [
            [
                'className' => \Library\Cli\Adapter\Mysql::class,
                'args' => [\Library\Cli\Models\Task::class]
            ],[
                'className' => \Library\Cli\Adapter\Log::class,
                'args' => ['logger'],
            ],
        ],
    ],
];
