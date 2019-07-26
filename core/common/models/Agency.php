<?php

namespace common\models;

use aminkt\metaActiveRecord\MetaActiveRecord;

/**
 * This is the model class for table "{{%agencies}}".
 *
 * @property int $id
 * @property string $name
 * @property int $owner_id
 * @property string $status
 * @property string $updated_at
 * @property string $created_at
 * @property string $address
 * @property string $phone
 *
 * @property User $owner
 * @property Tour[] $tours
 */
class Agency extends MetaActiveRecord
{
    use ActiveRecordTrait;

    const STATUS_ACTIVE = 'active';
    const STATUS_DEACTIVATE = 'deactivate';
    const STATUS_ALL = [
        self::STATUS_ACTIVE,
        self::STATUS_DEACTIVATE,
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agencies}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'address', 'phone'], 'required'],
            [['owner_id'], 'integer'],
            [['address', 'phone'], 'string'],
            [['status'], 'in', 'range' => self::STATUS_ALL],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['name'], 'string', 'max' => 191],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    /**
     * Owner of this agency.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::class, ['id' => 'owner_id']);
    }

    /**
     * List of tours that this agency owned.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTours()
    {
        return $this->hasMany(Tour::class, ['agency_id' => 'id']);
    }

    public function fields()
    {
        return array_merge(parent::fields(), $this->metaAttributes());
    }

    /**
     * @inheritDoc
     */
    function metaAttributes()
    {
        return [
            'address',
            'phone',
        ];
    }
}
