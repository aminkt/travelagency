<?php

namespace rest\versions\v1\controllers;

use common\models\Agency;
use aminkt\yii2\rest\controllers\RestControllerTrait;
use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;

/**
 * Class AgencyController
 * Agency controller will handle all actions of Agency model like create, update, listing and view.
 *
 * @package rest\versions\v1\controllers
 */
class AgencyController extends ActiveController
{
    use RestControllerTrait;

    public $modelClass = Agency::class;

    public $optionalAuthRoutes = ['index', 'view'];

    public $onlyAuthRoutes = ['create', 'update'];

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];

        return $actions;
    }

    /**
     * @inheritdoc
     */
    public function prepareDataProvider()
    {
        $query = Agency::find()->where(['status' => Agency::STATUS_ACTIVE]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        return $dataProvider;
    }
}
