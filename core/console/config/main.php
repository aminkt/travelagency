<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => [
                '@vendor/saghar/yii2-address-module/migrations',
                '@vendor/yii2mod/yii2-settings/migrations',
            ],
            'migrationNamespaces' => [
                'console\migrations'
            ]
        ],
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
        'fixture-generator' => [
            'class' => \yii\faker\FixtureController::class,
            'templatePath' => '@common/tests/templates/fixtures',
            'fixtureDataPath' => '@common/tests/_data',
            'providers' => [
                \common\tests\faker\Game::class,
            ],
        ]
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
