<?php

namespace common\models;

use saghar\address\models\Address;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tours}}".
 *
 * @property int $id
 * @property string $name
 * @property string $start_date
 * @property string $end_date
 * @property int $adult_capacity
 * @property int $child_capacity
 * @property int $origin_id
 * @property int $destination_id
 * @property string $tour_type
 * @property int $agency_id
 * @property int $leader_id
 * @property string $status
 * @property int $adult_unit_price
 * @property int $child_unit_price
 * @property string $updated_at
 * @property string $created_at
 *
 * @property User[] $users
 * @property Address $destination
 * @property Address $origin
 * @property Agency $agency
 * @property User $leader
 */
class Tour extends ActiveRecord
{
    use ActiveRecordTrait;

    const TYPE_INTERNAL = 'internal';
    const TYPE_FOREIGN = 'foreign';
    const TYPE_ALL = [
        self::TYPE_INTERNAL,
        self::TYPE_FOREIGN,
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_CANCEL = 'cancel';
    const STATUS_FINISH = 'finish';
    const STATUS_PRE_ORDER = 'pre_order';
    const STATUS_ALL = [
        self::STATUS_ACTIVE,
        self::STATUS_CANCEL,
        self::STATUS_FINISH,
        self::STATUS_PRE_ORDER,
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tours}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['start_date', 'end_date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['adult_capacity', 'child_capacity', 'origin_id', 'destination_id', 'agency_id', 'leader_id', 'adult_unit_price', 'child_unit_price'], 'integer'],
            [['tour_type'], 'in', 'range' => self::TYPE_ALL],
            [['status'], 'in', 'range' => self::STATUS_ALL],
            [['name'], 'string', 'max' => 191],
            [['destination_id'], 'exist', 'skipOnError' => true, 'targetClass' => Address::class, 'targetAttribute' => ['destination_id' => 'id']],
            [['origin_id'], 'exist', 'skipOnError' => true, 'targetClass' => Address::class, 'targetAttribute' => ['origin_id' => 'id']],
            [['agency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agency::class, 'targetAttribute' => ['agency_id' => 'id']],
            [['leader_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['leader_id' => 'id']],
        ];
    }

    /**
     * List of users that are registered for this tour.
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getUsers()
    {
        return $this
            ->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('{{%tour_user_relation}}', ['tour_id' => 'id']);
    }

    /**
     * Destination address of this tour.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDestination()
    {
        return $this->hasOne(Address::class, ['id' => 'destination_id']);
    }

    /**
     * Origin address of this tour.
     * @return \yii\db\ActiveQuery
     */
    public function getOrigin()
    {
        return $this->hasOne(Address::class, ['id' => 'origin_id']);
    }

    /**
     * Agency that owned this tour.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgency()
    {
        return $this->hasOne(Agency::class, ['id' => 'agency_id']);
    }

    /**
     * Leader that will lead this tour.
     * @return \yii\db\ActiveQuery
     */
    public function getLeader()
    {
        return $this->hasOne(User::class, ['id' => 'leader_id']);
    }
}
