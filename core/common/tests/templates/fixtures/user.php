<?php

// users.php file under the template path (by default @tests/unit/templates/fixtures)
/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use MongoDB\BSON\UTCDateTime;

return [
    'id' => rand(1, 10000000),
    'name' => $faker->firstName,
    'family' => $faker->lastName,
    'mobile' => \aminkt\normalizer\Normalize::normalizeMobile($faker->mobileNumber),
    'email' => $faker->email,
    'password_hash' => \Yii::$app->getSecurity()->generatePasswordHash('password_' . $index),
    'status' => $faker->randomElement([\common\models\User::STATUS_ACTIVE, \common\models\User::STATUS_BLOCK]),
    'is_deleted' => $faker->boolean,
    'update_at' => $faker->dateTime->format("Y-m-d H:i:s"),
    'create_at' => $faker->dateTime->format("Y-m-d H:i:s"),
];