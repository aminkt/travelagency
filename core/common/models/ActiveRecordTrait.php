<?php

namespace common\models;


use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * Class ActiveRecord
 * Base class for database models.
 *
 * @package common\models
 *
 * @property int $id
 */
trait ActiveRecordTrait
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    self::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // if you're using datetime instead of UNIX timestamp:
                'value' => new Expression("NOW()"),
            ],
        ];
    }

    /**
     * Get mongo id of model
     *
     * @return integer
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     *
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return cache key.
     *
     * @param string $name Name of cache attribute.
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function getCacheKey(string $name)
    {
        return [
            __CLASS__,
            'id' => $this->id,
            'name' => $name
        ];
    }
}