<?php
namespace rest\versions\v1;


use yii\base\Module;

/**
 * Class Module
 * API version 1
 *
 * @package api\modules\v1
 *
 * @author  Amin Keshavarz <amin@keshavarz.pro>
 */
class RestModule extends Module
{
    public $controllerNamespace = 'rest\versions\v1\controllers';

    public function init()
    {
        parent::init();
        \Yii::$app->user->enableSession = false;
    }
}