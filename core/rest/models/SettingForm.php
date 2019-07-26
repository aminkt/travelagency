<?php

namespace rest\models;

use \yii2mod\settings\components\Settings;
use yii\base\Model;

/**
 * SettingForm
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 */
class SettingForm extends Model
{
    public $section;
    public $key;
    public $value;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['section'], 'required'],
            // password is validated by validatePassword()
            [['value', 'section', 'key'], 'string'],
        ];
    }

    /**
     * Set setting.
     *
     * @return bool
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function saveSetting(): bool
    {
        if ($this->validate()) {
            return false;
        }

        /** @var Settings $settingComponent */
        $settingComponent = \Yii::$app->settings;
        if ($settingComponent->set($this->section, $this->key, $this->value)) {
            return true;
        }

        $this->addError('value', "Can't save settings.");
        return false;
    }

    /**
     * Return setting.
     *
     * @return string|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function readSetting(): ?string
    {
        if ($this->validate()) {
            return null;
        }

        /** @var Settings $settingComponent */
        $settingComponent = \Yii::$app->settings;
        return $this->key ?
            $settingComponent->get($this->section, $this->key) :
            $settingComponent->getAllBySection($this->section);
    }

    public function fields()
    {
        return [
            'section',
            'key',
            'value'
        ];
    }
}
