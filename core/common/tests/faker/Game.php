<?php

namespace common\tests\faker;

/**
 * Class Game
 * This class will provide some faker data created for game store tests.
 *
 * @package common\tests\faker
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
class Game extends \Faker\Provider\Base
{
    /** @var array $formats Format of game out put. */
    protected static $formats = array(
        '{{gameName}} {{gameSuffix}}',
        '{{gameName}}',
    );

    protected static $gameSuffix = array('II', 'IV', '2', '3', 'x', '', '19');

    protected static $gameName = [
        "Call of Duty", "Grand theft Auto", "God of war", "Tomb rider", "Need for Speed", "FIFA", "PES",
        "Read dead redemption", "Driver", "Assassins Creed", "Freoza", "Battle field", "NBA"
    ];

    /**
     * @example 'IV'
     */
    public static function gameSuffix()
    {
        return static::randomElement(static::$gameSuffix);
    }

    /**
     * @example 'GTA'
     */
    public static function gameName()
    {
        return static::randomElement(static::$gameName);
    }

    /**
     * @example 'Acme Ltd'
     */
    public function game()
    {
        $format = static::randomElement(static::$formats);

        return $this->generator->parse($format);
    }
}