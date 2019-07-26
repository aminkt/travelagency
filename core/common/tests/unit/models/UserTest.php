<?php

namespace common\tests\unit\models;

use common\models\User;
use common\tests\fixtures\UserFixture;
use Faker\Factory;

/**
 * Class UserTest
 * This class should test just user model works.
 *
 * @package common\tests\unit\models
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 */
class UserTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /**
     * @return array
     */
    public function _fixtures()
    {
        return [
            'User' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ];
    }

    public function _before()
    {

    }

    public function _after()
    {

    }


    /**
     * Test update action of User model.
     * We are using Factory object to create dynamic test cases.
     *
     * @param array $input
     * @param boolean $result
     *
     * @dataProvider updateUserDataProvider
     *
     * @throws \common\tests\_generated\ModuleException
     */
    public function testUpdate($input, $result)
    {
        $output = new \Codeception\Lib\Console\Output([]);
        $output->writeln("\n\n_____________ RUN Test update __________");
        $output->writeln("Result should be {$result}");
        $output->writeln(json_encode($input));
        $index = rand(0, 9);

        $output->writeln("Grab user fixture by id as user{$index}");
        /** @var User $User */
        $sample = $this->tester->grabFixture('User')->data['user' . $index];

        $user = User::findOne(['mobile' => $sample['mobile']]);

        $this->assertNotNull($user, "User object is valid");

        $user->load($input, '');

        $save = $user->save();

        $output->writeln("Real update result is {$save}");
        $output->writeln(json_encode($user->getAttributes()));

        if ($result) {
            $this->assertTrue($save, "User object updated.");
        } else {
            $this->assertFalse($save, "User object not updated.");
        }
    }

    /**
     * Data provider for testUpdate.
     *
     * @see UserTest::testUpdate()
     *
     * @return array
     */
    public function updateUserDataProvider()
    {
        // use the factory to create a Faker\Generator instance
        $fakerFa = Factory::create('fa_IR');
        $fakerEn = Factory::create();

        $doubleEmail = $fakerEn->email;
        $doubleMobile = $fakerFa->mobileNumber;

        return [
            [['name' => $fakerFa->name, 'family' => $fakerEn->lastName], 1],
            [['mobile' => '009390675600'], 1],
            [['mobile' => '09390675600'], 1],
            [['mobile' => '+989390675600'], 1],
            [['mobile' => '989390675600'], 1],
            [['mobile' => $doubleMobile], 1],
            [['email' => $fakerEn->domainName], 0],
            [['email' => $fakerFa->userName], 0],
            [['email' => $fakerFa->name], 0],
            [['email' => $doubleEmail], 1],
            [['status' => User::STATUS_ACTIVE], 1],
            [['status' => User::STATUS_BLOCK], 1],
            [['status' => $fakerEn->numberBetween(0, 100)], 0],
            [['status' => $fakerEn->name], 0],
        ];
    }
}
