<?php
return [
    // Home controller routes.
    'GET,OPTIONS v1' => '/v1/home',
    'POST,OPTIONS v1/signup' => '/v1/home/signup',
    'POST,OPTIONS v1/login' => '/v1/home/login',

    // Tour controller routes.
    'GET,OPTIONS v1/tours' => '/v1/tour/index',
    'POST,OPTIONS v1/tours' => '/v1/tour/create',
    'PUT,OPTIONS v1/tours/<id>' => '/v1/tour/update',
    'GET,OPTIONS v1/tours/cancel/<id>' => '/v1/tour/cancel-tour',
    'GET,OPTIONS v1/tours/active/<id>' => '/v1/tour/active-tour',
    'POST,OPTIONS v1/tours/register' => '/v1/tour/register-in-tour',

    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/agency',
        'except' => ['delete']
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'v1/user',
        'extraPatterns' => [
            'GET,OPTIONS profile' => 'profile',
        ],
    ],

    // Setting controller routes.
    'GET,OPTIONS v1/settings' => 'v1/setting/get',
    'POST,OPTIONS v1/settings' => 'v1/setting/set',
];