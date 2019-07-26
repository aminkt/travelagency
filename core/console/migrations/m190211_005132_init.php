<?php

namespace console\migrations;

use common\models\AbstractProduct;
use common\models\Agency;
use common\models\Tour;
use common\models\User;
use console\migrations\classes\MigrationTrait;
use yii\db\Migration;

/**
 * Class m190211_005132_init
 */
class m190211_005132_init extends Migration
{
    use MigrationTrait;

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable("{{%users}}", [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'family' => $this->string(),
            'mobile' => $this->string(15)->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string(),
            'status' => $this->enum(User::STATUS_ALL, User::STATUS_ACTIVE),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression("NOW()"),
            'created_at' => $this->dateTime()->notNull()->defaultExpression("NOW()")
        ], $this->tableOptions);

        $this->createTable("{{%tours}}", [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'start_date' => $this->dateTime(),
            'end_date' => $this->dateTime(),
            'adult_capacity' => $this->smallInteger(5),
            'child_capacity' => $this->smallInteger(5),
            'origin_id' => $this->integer(),
            'destination_id' => $this->integer(),
            'tour_type' => $this->enum(Tour::TYPE_ALL, Tour::TYPE_INTERNAL),
            'agency_id' => $this->integer(),
            'leader_id' => $this->integer(),
            'status' => $this->enum(Tour::STATUS_ALL, Tour::STATUS_PRE_ORDER),
            'adult_unit_price' => $this->integer(),
            'child_unit_price' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'created_at' => $this->dateTime(),
        ], $this->tableOptions);

        $this->addForeignKey(
            "tour_address_relations_origin",
            "{{%tours}}",
            "origin_id",
            "{{%address}}",
            "id",
            "CASCADE",
            "CASCADE"
        );

        $this->addForeignKey(
            "tour_address_relations_destination",
            "{{%tours}}",
            "destination_id",
            "{{%address}}",
            "id",
            "CASCADE",
            "CASCADE"
        );

        $this->addForeignKey(
            "tour_user_fk_tourLeader",
            "{{%tours}}",
            "leader_id",
            "{{%users}}",
            "id",
            "CASCADE",
            "CASCADE"
        );

        $this->createTable("{{%tour_user_relation}}", [
            'user_id' => $this->integer(),
            'tour_id' => $this->integer(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression("NOW()")
        ], $this->tableOptions);

        $this->addPrimaryKey(
            'tour_user_relation_pk',
            '{{%tour_user_relation}}',
            ['user_id', 'tour_id']
        );

        $this->addForeignKey(
            "tour_user_fk_relations_user",
            "{{%tour_user_relation}}",
            "user_id",
            "{{%users}}",
            "id",
            "CASCADE",
            "CASCADE"
        );

        $this->addForeignKey(
            "tour_user_fk_relations_tour",
            "{{%tour_user_relation}}",
            "tour_id",
            "{{%tours}}",
            "id",
            "CASCADE",
            "CASCADE"
        );

        $this->createTable("{{%address_user_relation}}", [
            'user_id' => $this->integer(),
            'address_id' => $this->integer(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression("NOW()")
        ], $this->tableOptions);

        $this->addPrimaryKey(
            'address_user_pk',
            '{{%address_user_relation}}',
            ['user_id', 'address_id']
        );

        $this->addForeignKey(
            "address_user_relations_user",
            "{{%address_user_relation}}",
            "user_id",
            "{{%users}}",
            "id",
            "CASCADE",
            "CASCADE"
        );

        $this->addForeignKey(
            "address_user_relations_address",
            "{{%address_user_relation}}",
            "address_id",
            "{{%address}}",
            "id",
            "CASCADE",
            "CASCADE"
        );

        $this->createTable("{{%agencies}}", [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'owner_id' => $this->integer(),
            'status' => $this->enum(Agency::STATUS_ALL, Agency::STATUS_ACTIVE),
            'updated_at' => $this->dateTime()->notNull()->defaultExpression("NOW()"),
            'created_at' => $this->dateTime()->notNull()->defaultExpression("NOW()")
        ], $this->tableOptions);

        $this->addForeignKey(
            "tour_agency_fk_agency",
            "{{%tours}}",
            "agency_id",
            "{{%agencies}}",
            "id",
            "CASCADE",
            "CASCADE"
        );

        $this->addForeignKey(
            "agency_user_fk_owner",
            "{{%agencies}}",
            "owner_id",
            "{{%users}}",
            "id",
            "CASCADE",
            "CASCADE"
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190211_005132_init cannot be reverted.\n";

        return false;
    }
}
