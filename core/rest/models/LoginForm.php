<?php

namespace rest\models;

use aminkt\normalizer\yii2\MoblieValidatoer;
use common\models\User;
use yii\base\Model;

/**
 * Login form
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 */
class LoginForm extends Model
{
    public $mobile;
    public $password;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['mobile', 'password'], 'required'],
            [['mobile'], MoblieValidatoer::class],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect mobile number or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return string|null User token or null if login become failed.
     */
    public function login(): ?string
    {
        if ($this->validate()) {
            $token = $this->getUser()->generateToken();
            return $token;
        }
        
        return null;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    private function getUser(): ?User
    {
        if ($this->_user === null) {
            $this->_user = User::findByMobile($this->mobile);
        }

        return $this->_user;
    }
}
