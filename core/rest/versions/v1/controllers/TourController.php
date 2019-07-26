<?php

namespace rest\versions\v1\controllers;

use aminkt\yii2\rest\controllers\RestControllerTrait;
use common\models\Tour;
use rest\models\RegisterTourForm;
use rest\models\TourForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class TourController
 * Handle all actions about tour.
 *
 * @package rest\versions\v1\controllers
 */
class TourController extends Controller
{
    use RestControllerTrait;

    public $optionalAuthRoutes = ['index', 'login', 'signup'];


    /**
     * List of available tours.
     *
     * @return ActiveDataProvider
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Tour::find()
        ]);

        return $dataProvider;
    }

    /**
     * Create new tour.
     *
     * @return array|\common\models\Tour
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function actionCreate()
    {
        $tourForm = new TourForm();
        if (!$tourForm->load(Yii::$app->getRequest()->post(), '')) {
            throw new BadRequestHttpException("Can't load data.");
        }

        if ($tour = $tourForm->create()) {
            return $tour;
        } elseif ($tourForm->hasErrors()) {
            return $tourForm->getErrors();
        }

        throw new ServerErrorHttpException("Can't save tour");
    }

    /**
     * Update a tour.
     *
     * @param int $id Id of tour.
     *
     * @return array|Tour
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function actionUpdate($id)
    {
        $tourForm = new TourForm();
        if (!$tourForm->load(Yii::$app->getRequest()->post(), '')) {
            throw new BadRequestHttpException("Can't load data.");
        }

        if ($tour = $tourForm->update($id)) {
            return $tour;
        } elseif ($tourForm->hasErrors()) {
            return $tourForm->getErrors();
        }

        throw new ServerErrorHttpException("Can't save tour");
    }

    /**
     * Cancel a tour by id.
     * @param int $id   Id of tour.
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function actionCancelTour($id)
    {
        return TourForm::cancelTour($id) ?
            $this->success("Tour canceled") :
            $this->error("Can not cancel tour.", 500);
    }

    /**
     * Active a tour.
     *
     * @param $id
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function actionActiveTour($id)
    {
        return TourForm::activeTour($id) ?
            $this->success("Tour Activated") :
            $this->error("Can not active tour.", 500);
    }

    /**
     * Register a user in tour.
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \yii\web\BadRequestHttpException
     * @throws \yii\web\ServerErrorHttpException
     */
    public function actionRegisterInTour()
    {
        $regForm = new RegisterTourForm();
        if (!$regForm->load(Yii::$app->getRequest()->post(), '')) {
            throw new BadRequestHttpException("Can't load data.");
        }

        if ($regForm->register()) {
            return $this->success("You registered in tour successfully");
        } elseif ($regForm->hasErrors()) {
            return $regForm->errors;
        }

        throw new ServerErrorHttpException("Can not register user in tour");
    }
}