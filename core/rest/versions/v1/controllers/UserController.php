<?php

namespace rest\versions\v1\controllers;

use common\models\User;
use aminkt\yii2\rest\controllers\RestControllerTrait;
use Yii;
use yii\rest\ActiveController;

/**
 * Class UserController
 * User controller will handle all actions of user model like create, update, listing and view profile.
 *
 * @package rest\versions\v1\controllers
 */
class UserController extends ActiveController
{
    use RestControllerTrait;

    public $modelClass = User::class;

    public $onlyAuthRoutes = ['profile', 'update', 'view', 'index'];

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['delete']);
        return $actions;
    }

    /**
     * Profile action will return profile data of user.
     */
    public function actionProfile()
    {
        return User::findOne(Yii::$app->getUser()->getId());
    }
}
