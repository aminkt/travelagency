<?php


namespace common\tests\faker;

/**
 * Class Factory
 * Create faker items and include project fakers too.
 *
 * @package common\tests\faker
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
class Factory extends \Faker\Factory
{
    /**
     * @inheritdoc
     */
    public static function create($locale = \Faker\Factory::DEFAULT_LOCALE)
    {
        $faker = \Faker\Factory::create($locale);
        $faker->addProvider(new Game($faker));
        return $faker;
    }
}