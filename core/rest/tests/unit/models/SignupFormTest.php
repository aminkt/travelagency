<?php
namespace rest\tests\unit\models;

use aminkt\normalizer\Normalize;
use common\tests\fixtures\UserFixture;
use Faker\Factory;
use rest\models\SignupForm;

/**
 * Class SignupFormTest
 * This class will test signup form.
 *
 * @package rest\tests\unit\models
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 */
class SignupFormTest extends \Codeception\Test\Unit
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
            ]
        ]);
    }

    /**
     * Test that signup work with correct inputs.
     *
     * @throws \yii\base\Exception
     */
    public function testCorrectSignup()
    {
        $fakerFa = Factory::create('fa_IR');

        $input = [
            'name' => $fakerFa->name,
            'family' => $fakerFa->lastName,
            'mobile' => $fakerFa->mobileNumber,
            'email' => $fakerFa->email,
            'password' => 'some_password',
        ];

        $model = new SignupForm($input);

        $user = $model->signup();

        expect($user)->isInstanceOf('common\models\User');

        expect($user->mobile)->equals(Normalize::normalizeMobile($input['mobile']));
        expect($user->email)->equals($input['email']);
        expect($user->validatePassword($input['password']))->true();
    }
}
