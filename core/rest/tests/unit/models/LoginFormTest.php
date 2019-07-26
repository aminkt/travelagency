<?php

namespace rest\tests\unit\models;

use aminkt\normalizer\Normalize;
use common\models\User;
use common\tests\fixtures\UserFixture;
use rest\models\LoginForm;

/**
 * Class LoginFormTest
 * This class will test login form.
 *
 * @package rest\tests\unit\models
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 */
class LoginFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \rest\tests\UnitTester
     */
    protected $tester;


    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::className(),
                'dataFile' => \Yii::getAlias('@common') . '/tests/_data/user.php'
            ]
        ]);
    }

    /**
     * Test that login work with correct inputs.
     *
     * @throws \rest\tests\_generated\ModuleException
     */
    public function testCorrectLogin()
    {
        $output = new \Codeception\Lib\Console\Output([]);

        $index = rand(0, 9);
        $output->writeln("\n\nGrab user fixture by id as user{$index}");
        $sample = $this->tester->grabFixture('user')->data['user' . $index];

        $input = [
            'mobile' => $sample['mobile'],
            'password' => 'password_' . $index,
        ];

        $model = new LoginForm($input);
        $user = User::findOne(['mobile' => $sample['mobile']]);

        expect($user)->notNull();

        if ($model->hasErrors()) {
            $output->writeln($model->getFirstErrors());
        }

        $token = $model->login();

        if ($user->status == User::STATUS_BLOCK) {
            $output->writeln("User is block. Token do not generated!");
            expect($token)->null();
        } else {
            $output->writeln("User is valid. token generated.");
            /** @var User|null $user */
            $user = User::findIdentityByAccessToken($token, 'yii\filters\auth\HttpBearerAuth');

            expect($user)->isInstanceOf('common\models\User');
            expect($user->mobile)->equals(Normalize::normalizeMobile($sample['mobile']));
        }

    }
}
