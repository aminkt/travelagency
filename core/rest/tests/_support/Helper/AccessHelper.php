<?php

namespace rest\tests\Helper;

use Codeception\Util\HttpCode;
use Faker\Factory;
use Faker\Generator;
use rest\tests\ApiTester;

/**
 * Class AccessHelper
 * @package rest\tests\Helper
 */
class AccessHelper extends \Codeception\Module
{
    /** @var Generator */
    public $fakerEn;

    /** @var Generator */
    public $fakerFa;


    public function _initialize()
    {
        parent::_initialize();
        $this->fakerFa = Factory::create('fa_IR');
        $this->fakerEn = Factory::create();
    }

    /**
     * Get user profile.
     *
     * @param string $token User token.
     *
     * @return array    Return user profile.
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function getProfile($token)
    {
        /** @var ApiTester $apiTester */
        $apiTester = $this->getModule('REST');

        $apiTester->haveHttpHeader('Content-Type', 'application/json');
        $apiTester->amBearerAuthenticated($token);
        $apiTester->sendGET('v1/users/profile');

        $apiTester->seeResponseCodeIs(HttpCode::OK);
        $apiTester->seeResponseIsJson();

        $response = json_decode($apiTester->grabResponse());

        $apiTester->assertTrue($response->success);

        return (array)$response->data;
    }

    /**
     * Generate new token. Return full user data like this:
     * <code>
     * [
     *      'name' => "Firs name",
     *      'family' => "Last name",
     *      'mobile' => "Mobile",
     *      'email' => "Email",
     *      'password' => 'Password',
     *      'access_token' => 'token',
     *      'token_type' => 'Bearer'
     * ]
     * </code>
     *
     * @return array    Login data
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function getNewToken()
    {
        $user = $this->signup(
            $this->fakerFa->firstName,
            $this->fakerFa->lastName,
            $this->fakerFa->mobileNumber,
            $this->fakerFa->email,
            $this->fakerEn->password()
        );
        $token = $this->login($user['mobile'], $user['password']);

        return array_merge($user, $token);
    }

    /**
     * Sign up a new user by fake data and return user data.
     * Return value is something like this:
     * <code>
     * [
     *      'name' => "Firs name",
     *      'family' => "Last name",
     *      'mobile' => "Mobile",
     *      'email' => "Email",
     *      'password' => 'Password',
     * ]
     * </code>
     *
     * @param string $name User name
     * @param string $family User family
     * @param string $mobile User mobile number
     * @param string $email User email
     * @param string $password User password
     *
     * @return array User date.
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function signup($name, $family, $mobile, $email, $password)
    {
        /** @var ApiTester $apiTester */
        $apiTester = $this->getModule('REST');
        $apiTester->haveHttpHeader('Content-Type', 'application/json');
        $input = [
            'name' => $name,
            'family' => $family,
            'mobile' => $mobile,
            'email' => $email,
            'password' => $password
        ];

        $apiTester->sendPOST('v1/signup', $input);

        $apiTester->seeResponseIsJson();
        $apiTester->seeResponseCodeIs(HttpCode::OK);

        $response = json_decode($apiTester->grabResponse());

        $apiTester->assertTrue($response->success);
        $apiTester->assertEquals(
            "You are registered successfully. for login please send login request.",
            $response->data->message
        );

        return $input;
    }

    /**
     * Login a new user and return login data.
     * Return array is something like this:
     * <code>
     * [
     *      'access_token' => 'token',
     *      'token_type' => 'Bearer'
     * ]
     * </code>
     *
     * @param string $mobile User mobile number
     * @param string $password User password.
     *
     * @return array    Login data if successful.
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function login($mobile, $password)
    {
        /** @var ApiTester $apiTester */
        $apiTester = $this->getModule('REST');
        $apiTester->haveHttpHeader('Content-Type', 'application/json');
        $input = [
            'mobile' => $mobile,
            'password' => $password,
        ];
        $apiTester->sendPOST('v1/login', $input);

        $apiTester->seeResponseIsJson();
        $apiTester->seeResponseCodeIs(HttpCode::OK);

        $response = json_decode($apiTester->grabResponse());

        $apiTester->assertTrue($response->success);

        return (array)$response->data;
    }
}
