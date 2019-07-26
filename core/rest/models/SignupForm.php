<?php

namespace rest\models;

use aminkt\normalizer\yii2\MoblieValidatoer;
use common\models\User;
use yii\base\Model;

/**
 * Signup form
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 */
class SignupForm extends Model
{
    public $name;
    public $family;
    public $mobile;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mobile', 'name', 'email'], 'required'],
            [['name', 'family'], 'string', 'min' => 3],
            [['mobile'], MoblieValidatoer::class],
            ['mobile', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This mobile has already been taken.'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     *
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->name = $this->name;
        $user->family = $this->family;
        $user->mobile = $this->mobile;
        $user->email = $this->email;
        $user->setPassword($this->password);

        $user->save();

        return $user;
    }
}