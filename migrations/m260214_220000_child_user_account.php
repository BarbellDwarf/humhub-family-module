<?php

use humhub\components\Migration;

class m260214_220000_child_user_account extends Migration
{
    public function safeUp()
    {
        $this->dropForeignKey('fk-child-mother_id', 'child');
        $this->dropForeignKey('fk-child-father_id', 'child');
        $this->dropIndex('idx-child-mother_id', 'child');
        $this->dropIndex('idx-child-father_id', 'child');
        $this->dropColumn('child', 'mother_id');
        $this->dropColumn('child', 'father_id');

        $this->alterColumn('child', 'birth_date', $this->date()->null());
        $this->addColumn('child', 'child_user_id', $this->integer()->null());
        $this->createIndex('idx-child-child_user_id', 'child', 'child_user_id');
        $this->addForeignKey(
            'fk-child-child_user_id',
            'child',
            'child_user_id',
            'user',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-child-child_user_id', 'child');
        $this->dropIndex('idx-child-child_user_id', 'child');
        $this->dropColumn('child', 'child_user_id');

        $this->alterColumn('child', 'birth_date', $this->date()->notNull());
        $this->addColumn('child', 'mother_id', $this->integer()->null());
        $this->addColumn('child', 'father_id', $this->integer()->null());
        $this->createIndex('idx-child-mother_id', 'child', 'mother_id');
        $this->createIndex('idx-child-father_id', 'child', 'father_id');
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
}
