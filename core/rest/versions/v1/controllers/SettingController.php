<?php

namespace rest\versions\v1\controllers;

use aminkt\yii2\rest\controllers\RestControllerTrait;
use rest\models\SettingForm;
use Yii;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;

/**
 * Class HomeController
 *
 * @package rest\versions\v1\controllers
 */
class SettingController extends Controller
{
    use RestControllerTrait;

    public $optionalAuthRoutes = ['get'];
    public $onlyAuthRoutes = ['set'];

    /**
     * Set controller handle add and update actions of settings.
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionSet()
    {
        $settingForm = new SettingForm();
        if ($settingForm->load(Yii::$app->getRequest()->post(), '')) {
            if ($settingForm->saveSetting()) {
                return $settingForm;
            } else {
                return $settingForm->getErrors();
            }
        }

        throw new BadRequestHttpException("Cant load settings.");
    }

    /**
     * Get setting by section and key.
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionGet()
    {
        $settingForm = new SettingForm();
        if ($settingForm->load(Yii::$app->getRequest()->post(), '')) {
            if ($val = $settingForm->readSetting()) {
                return [
                    'value' => $val
                ];
            } else {
                return $settingForm->getErrors();
            }
        }

        throw new BadRequestHttpException("Cant load settings.");
    }
}