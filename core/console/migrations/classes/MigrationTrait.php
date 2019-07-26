<?php


namespace console\migrations\classes;


use InvalidArgumentException;
use yii\db\ColumnSchemaBuilder;

/**
 * Trait EnumTrait
 * Add enum col generator to migration class.
 *
 * @package console\migrations
 *
 * @author  Amin Keshavarz <ak_1596@yahoo.com>
 */
trait MigrationTrait
{
    public $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    /**
     * Generate enum col.
     *
     * @param array       $params
     * @param null|string $default
     *
     * @return string
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public function enum(array $params, string $default = null): string
    {
        if (!is_array($params) or count($params) == 0) {
            throw new InvalidArgumentException("Params should be array.");
        }
        $sql = "ENUM(";
        for ($i = 0; $i < count($params); $i++) {
            if ($i != 0) {
                $sql .= ", ";
            }
            $sql .= "'{$params[$i]}'";
        }
        $sql .= ")";

        if ($default) {
            $sql .= " NOT NULL DEFAULT '{$default}'";
        }

        return $sql;
    }

    /**
     * Creates a string column.
     *
     * @param int $length column size definition i.e. the maximum string length.
     *                    This parameter will be ignored if not supported by the DBMS.
     *
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function string($length = 191)
    {
        return parent::string($length);
    }
}