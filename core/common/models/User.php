<?php

namespace common\models;

use aminkt\normalizer\Normalize;
use aminkt\normalizer\yii2\MoblieValidatoer;
use aminkt\yii2\oauth2\jwt\UserTrait;
use saghar\address\models\Address;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property int       $id
 * @property string    $name
 * @property string    $family
 * @property string    $mobile
 * @property string    $email
 * @property string    $password_hash
 * @property string    $status
 * @property string    $updated_at
 * @property string    $created_at
 *
 * @property string    $password
 * @property Address[] $addresses
 * @property Agency[]  $agenciesOwned
 * @property Agency[]  $agenciesUsed
 * @property Tour[]    $tours
 * @property Tour[]    $toursLeaded
 *
 * @property string    $USER                [char(32)]
 * @property int       $CURRENT_CONNECTIONS [bigint(20)]
 * @property int       $TOTAL_CONNECTIONS   [bigint(20)]
 */
class User extends ActiveRecord implements IdentityInterface
{
    use UserTrait {
        generateToken as protected traitGenerateToken;
    }
    use ActiveRecordTrait;

    const STATUS_ACTIVE = 'active';
    const STATUS_BLOCK = 'blocked';
    const STATUS_ALL = [
        self::STATUS_ACTIVE,
        self::STATUS_BLOCK,
    ];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     *
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return User::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds an identity by the given mobile.
     *
     * @param string $mobile the mobile to be looked for
     *
     * @return User|null the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findByMobile($mobile): ?User
    {
        $mobile = Normalize::normalizeMobile($mobile);
        return User::findOne(['mobile' => $mobile, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function getEncryptKey()
    {
        return Yii::$app->params['tokenEncryptKey'];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'mobile', 'email'], 'required'],
            [['status'], 'in', 'range' => self::STATUS_ALL],
            [['name', 'family', 'email'], 'string', 'max' => 191],
            [['mobile'], MoblieValidatoer::class],
            [['mobile'], 'unique'],
            [['email'], 'unique'],
            [['email'], 'email'],
        ];
    }

    /**
     * List of user addresses.
     *
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getAddresses()
    {
        return $this
            ->hasMany(Address::class, ['id' => 'address_id'])
            ->viaTable('{{%address_user_relation}}', ['user_id' => 'id']);
    }

    /**
     * List of agency that this user is owner.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgenciesOwned()
    {
        return $this->hasMany(Agency::class, ['owner_id' => 'id']);
    }

    /**
     * List of agency that this user to travel.
     *
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getAgenciesUsed()
    {
        return $this
            ->hasMany(Agency::class, ['owner_id' => 'id'])
            ->viaTable('{{%tours}}', ['agency_id' => 'id']);
    }

    /**
     * List of user tours.
     *
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getTours()
    {
        return $this->hasMany(Tour::class, ['id' => 'tour_id'])->viaTable('{{%tour_user_relation}}', ['user_id' => 'id']);
    }

    /**
     * List of tours that user leaded.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getToursLeaded()
    {
        return $this->hasMany(Tour::class, ['leader_id' => 'id']);
    }

    /**
     * Generate a new token and store service token in db.
     *
     * @param null|array $payload   Payload part of JWT token that will be generate.
     *
     * @return string   Token that generated.
     */
    public function generateToken($payload = null)
    {
        if (!$payload) {
            $payload = $this->payloadCreator();
            unset($payload['exp']);
        }

        $token = $this->traitGenerateToken($payload);

        return $token;
    }

    /**
     * Validate user input password.
     *
     * @param $password
     *
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password_hash);
    }

    /**
     * Set password for user.
     *
     * @param $password
     *
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
    }


    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();
        unset($fields['password_hash'], $fields['is_deleted']);
        return $fields;
    }
}
