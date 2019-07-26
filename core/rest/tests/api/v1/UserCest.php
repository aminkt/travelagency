<?php

namespace rest\tests;

use aminkt\normalizer\Normalize;
use Codeception\Util\Fixtures;
use Codeception\Util\HttpCode;
use Faker\Factory;

class UserCest
{
    public function _before(ApiTester $I)
    {
    }

    /**
     * Test user sign up.
     *
     * @param \rest\tests\ApiTester $I
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function signupTest(ApiTester $I)
    {
        $fakerFa = Factory::create('fa_IR');

        $I->signup(
            $fakerFa->firstName,
            $fakerFa->lastName,
            $fakerFa->mobileNumber,
            $fakerFa->email,
            'test_password'
        );
    }

    /**
     * Test user login.
     *
     * @param ApiTester $I
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function loginTest(ApiTester $I)
    {
        $fakerFa = Factory::create('fa_IR');

        $userRegistrationData = $I->signup(
            $fakerFa->firstName,
            $fakerFa->lastName,
            $fakerFa->mobileNumber,
            $fakerFa->email,
            'test_password'
        );
        $data = $I->login($userRegistrationData['mobile'], $userRegistrationData['password']);
    }

    /**
     * Test get login user profile.
     *
     * @param ApiTester $I
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function getProfileTest(ApiTester $I)
    {
        $user = $I->getNewToken();

        $I->wantTo("Get login user profile");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amBearerAuthenticated($user['access_token']);
        $I->sendGET('v1/users/profile');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $response = json_decode($I->grabResponse());

        $I->assertTrue($response->success);
        $I->assertEquals($user['name'], $response->data->name);
        $I->assertEquals($user['family'], $response->data->family);
        $I->assertEquals($user['email'], $response->data->email);
        $I->assertTrue(empty($response->data->password_hash));
        $I->assertTrue(empty($response->data->is_deleted));
        $I->assertEquals(Normalize::normalizeMobile($user['mobile']), $response->data->mobile);
    }

    /**
     * Test to get list of users.
     *
     * @param ApiTester $I
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function getListOfUsersTest(ApiTester $I)
    {
        $user = $I->getNewToken();

        $I->wantTo("Get list of users");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amBearerAuthenticated($user['access_token']);
        $I->sendGET('v1/users');

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $response = json_decode($I->grabResponse());

        $I->assertTrue($response->success);
    }

    /**
     * View a user profile by id.
     *
     * @param ApiTester $I
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function viewUserProfileTest(ApiTester $I)
    {
        $user = $I->getNewToken();
        $profile = $I->getProfile($user['access_token']);

        $I->wantTo("Get user profile by id.");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amBearerAuthenticated($user['access_token']);
        $I->sendGET("v1/users/{$profile['id']}");

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $response = json_decode($I->grabResponse());

        $I->assertTrue($response->success);
        $I->assertEquals($user['name'], $response->data->name);
        $I->assertEquals($user['family'], $response->data->family);
        $I->assertEquals($user['email'], $response->data->email);
        $I->assertEquals(Normalize::normalizeMobile($user['mobile']), $response->data->mobile);
    }

    /**
     * Test update user.
     *
     * @param ApiTester $I
     *
     * @throws \Codeception\Exception\ModuleException
     */
    public function updateUserTest(ApiTester $I)
    {
        $fakerFa = Factory::create('fa_IR');

        $user = $I->getNewToken();
        $profile = $I->getProfile($user['access_token']);

        $I->wantTo("Update user.");
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amBearerAuthenticated($user['access_token']);
        $input = [
            'name' => $fakerFa->firstName,
            'family' => $fakerFa->lastName
        ];
        $I->sendPUT("v1/users/{$profile['id']}", $input);

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $response = json_decode($I->grabResponse());

        $I->assertTrue($response->success);
        $I->assertEquals($input['name'], $response->data->name);
        $I->assertEquals($input['family'], $response->data->family);

    }
}
