<?php

namespace console\controllers\crons;

use common\models\Tour;
use yii\console\Controller;
use yii\db\Query;

/**
 * Class TourCronJobController
 * Handle cron jobs that work on tours.
 *
 * @package console\controllers
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
class TourCronJobController extends Controller
{
    /**
     * Using this cron to find tours that finished.
     * You should config this tours in your server cron manually.
     *
     * @return void
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function actionMarkToursAsFinished()
    {
        $query = new Query();
        $query
            ->select(['id'])
            ->from(Tour::tableName())
            ->where('end_date > NOW() and status != :status', [
                ':status' => Tour::STATUS_FINISH
            ])
            ->limit(1000);
        $rows = $query->all();
        $tourIds = array_column($rows, 'id');

        Tour::updateAll(
            [
                'status' => Tour::STATUS_FINISH
            ],
            'id IN(:ids)',
            [
                ':ids' => $tourIds
            ]
        );
    }
}