<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'travelagency-api',
    'name' => 'travelagency',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'en',
    'defaultRoute' => 'v1/home',
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' => \yii\web\Response::FORMAT_JSON,
            'on beforeSend' => function ($event) {
                $debugRoute = preg_match('%debug|gii%', Yii::$app->getRequest()->getPathInfo());
                if (!(YII_DEBUG and $debugRoute)) {
                    $response = $event->sender;
                    if ($response->data !== null) {
                        if (!$response->isSuccessful) {
                            if (isset($response->data['type'])) {
                                unset($response->data['type']);
                            }
                        }
                        if (isset($response->data['data'])) {
                            $response->data['success'] = $response->isSuccessful;
                        } else {
                            $response->data = [
                                'success' => $response->isSuccessful,
                                'data' => $response->data,
                            ];
                        }
                    }
                }
            },
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG, // use "pretty" output in debug mode
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ],
            ],
        ],
        'user' => [
            'identityClass' => \common\models\User::class,
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => require('routes.php')
        ],
    ],
    'modules' => [
        'v1' => \rest\versions\v1\RestModule::class,
        'uploadManager' => [
            'class' => aminkt\yii2\uploadmanager\UploadManager::class,
            'allowedFiles' => ['jpeg', 'jpg', 'png', 'gif', 'mp4'],
            'uploadPath' => Yii::getAlias('@upload'),
            'userClass' => \common\models\User::class,
            'fileClass' => \aminkt\yii2\uploadmanager\models\File::class,
            'fileSearchClass' => \aminkt\yii2\uploadmanager\models\FileSearch::class,
        ],
        'category' => [
            'class' => \saghar\category\Category::class,
            'modelClass' => \common\models\Category::class,
            'searchModelClass' => \saghar\category\models\CategorySearch::class
        ],
    ],
    'params' => $params,
];
