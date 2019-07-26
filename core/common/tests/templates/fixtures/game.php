<?php

// game.php file under the template path (by default @tests/unit/templates/fixtures)

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

use MongoDB\BSON\UTCDateTime;

return [
    'id' => rand(1, 10000000),
    'name' => 'main',
    'description' => $faker->realText(),
    'tags' => $faker->randomElement([\common\models\Category::STATUS_ACTIVE, \common\models\Category::STATUS_REMOVED]),
    'update_at' => $faker->dateTime->format("Y-m-d H:i:s"),
    'create_at' => $faker->dateTime->format("Y-m-d H:i:s"),
];