<?php

use humhub\components\Migration;

/**
 * Initial migration for Family Management module
 *
 * Creates the 'child' table for storing children profiles linked to user accounts.
 *
 * Table Structure:
 * - id: Primary key
 * - user_id: Parent user reference (required, FK to user table)
 * - first_name: Child's first name (required, max 100 chars)
 * - last_name: Child's last name (required, max 100 chars)
 * - birth_date: Date of birth (required)
 * - mother_id: Optional reference to user acting as mother (FK to user table)
 * - father_id: Optional reference to user acting as father (FK to user table)
 * - created_at: Record creation timestamp
 * - updated_at: Record update timestamp
 *
 * Foreign Keys:
 * - user_id → user.id (CASCADE on delete - removes children if parent deleted)
 * - mother_id → user.id (SET NULL on delete - preserves child if mother deleted)
 * - father_id → user.id (SET NULL on delete - preserves child if father deleted)
 *
 * Indexes:
 * - user_id for quick lookup of parent's children
 * - birth_date for birthday calendar queries
 * - mother_id and father_id for relationship queries
 */
class m260206_220000_initial extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('child', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'first_name' => $this->string(100)->notNull(),
            'last_name' => $this->string(100)->notNull(),
            'birth_date' => $this->date()->notNull(),
            'mother_id' => $this->integer()->null(),
            'father_id' => $this->integer()->null(),
            'created_at' => $this->integer()->null(),
            'updated_at' => $this->integer()->null(),
        ]);

        $this->createIndex('idx-child-user_id', 'child', 'user_id');
        $this->createIndex('idx-child-birth_date', 'child', 'birth_date');
        $this->createIndex('idx-child-mother_id', 'child', 'mother_id');
        $this->createIndex('idx-child-father_id', 'child', 'father_id');

        $this->addForeignKey(
            'fk-child-user_id',
            'child',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-child-mother_id',
            'child',
            'mother_id',
            'user',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-child-father_id',
            'child',
            'father_id',
            'user',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-child-father_id', 'child');
        $this->dropForeignKey('fk-child-mother_id', 'child');
        $this->dropForeignKey('fk-child-user_id', 'child');

        $this->dropTable('child');
    }
}
