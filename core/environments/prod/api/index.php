<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/vendor/yiisoft/yii2/Yii.php';
require dirname(__DIR__) . '/core/common/config/bootstrap.php';
require dirname(__DIR__) . '/core/rest/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require dirname(__DIR__) . '/core/common/config/main.php',
    require dirname(__DIR__) . '/core/common/config/main-local.php',
    require dirname(__DIR__) . '/core/rest/config/main.php',
    require dirname(__DIR__) . '/core/rest/config/main-local.php'
);

(new yii\web\Application($config))->run();
