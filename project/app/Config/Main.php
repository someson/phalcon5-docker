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
    'auth' => [
        'identityName' => env('AUTH_IDENTITY'),
    ],
    'crypt' => [
        'salt' => env('CRYPT_SALT'),
        'key'  => env('CRYPT_KEY'),
    ],
    'i18n' => [
        'loader' => [
            'arguments' => ['path' => TMP_DIR . '/locale/'],
        ],
        'collectMissingTranslations' => false,
        'decorateMissingTranslations' => '[# %s #]',
    ],
];
