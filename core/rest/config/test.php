<?php
return [
    'id' => 'app-rest-tests',
    'components' => [
        'assetManager' => [
            'basePath' => dirname(dirname(dirname(__DIR__))) . '/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
        ],
    ],
];
