<?php

namespace rest\versions\v1\controllers;

use aminkt\yii2\rest\controllers\RestControllerTrait;
use aminkt\yii2\rest\utils\HttpCode;
use common\models\Game;
use rest\models\LoginForm;
use rest\models\SignupForm;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;

/**
 * Class HomeController
 *
 * @package rest\versions\v1\controllers
 */
class HomeController extends Controller
{
    use RestControllerTrait;

    public $optionalAuthRoutes = ['index', 'login', 'signup'];

    public function actionIndex()
    {
        return ['Welcome to version 1 of travel agency API'];
    }

    /**
     * Login user by username and password.
     *
     * @return array
     *
     * @throws BadRequestHttpException
     */
    public function actionLogin()
    {
        $model = new LoginForm();

        if ($model->load(\Yii::$app->getRequest()->post(), '')) {
            $token = $model->login();
            if ($token) {
                return $this->success([
                    "access_token" => $token,
                    "token_type" => "Bearer",
                ]);
            } else {
                return $this->error($model->getErrors());
            }
        } else {
            throw new BadRequestHttpException("Please send your mobile number and password.");
        }
    }

    /**
     * Sign up as a user in system.
     *
     * @throws BadRequestHttpException
     * @throws \yii\base\Exception
     */
    public function actionSignup()
    {
        $model = new SignupForm();

        if ($model->load(\Yii::$app->getRequest()->post(), '')) {
            $user = $model->signup();
            if ($user === null) {
                return $this->error($model->getErrors(), HttpCode::BAD_REQUEST);
            } else {
                if ($user->hasErrors()) {
                    return $this->error($user->getErrors(), HttpCode::BAD_REQUEST);
                } else {
                    return $this->success([
                        'message' => 'You are registered successfully. for login please send login request.',
                    ]);
                }
            }
        } else {
            throw new BadRequestHttpException("Please send your mobile number and password.");
        }
    }
}