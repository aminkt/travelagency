<?php

namespace rest\models;

use common\models\Tour;
use common\models\User;
use yii\base\Model;

/**
 * RegisterTourForm
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 */
class RegisterTourForm extends Model
{
    public $user_id;
    public $tour_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'tour_id'], 'required'],
            [['user_id', 'tour_id'], 'integer'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['tour_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tour::class, 'targetAttribute' => ['tour_id' => 'id']],
        ];
    }

    /**
     * Register a user in tour.
     *
     * @return bool
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function register(): bool
    {
        if (!$this->validate()) {
            return false;
        }
        $tour = Tour::findOne($this->tour_id);
        $user = User::findOne($this->user_id);
        if (!$tour or !$user) {
            return false;
        }

        $tour->link('users', $user);
        return true;
    }
}
