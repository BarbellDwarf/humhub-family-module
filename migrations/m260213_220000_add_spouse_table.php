<?php

use yii\db\Migration;

class m260213_220000_add_spouse_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%spouse}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'spouse_user_id' => $this->integer()->null(),
            'first_name' => $this->string(100)->null(),
            'last_name' => $this->string(100)->null(),
            'birth_date' => $this->date()->null(),
            'email' => $this->string(255)->null(),
            'phone' => $this->string(50)->null(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk-spouse-user_id',
            '{{%spouse}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-spouse-spouse_user_id',
            '{{%spouse}}',
            'spouse_user_id',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->createIndex('idx-spouse-user_id', '{{%spouse}}', 'user_id', true);
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-spouse-spouse_user_id', '{{%spouse}}');
        $this->dropForeignKey('fk-spouse-user_id', '{{%spouse}}');
        $this->dropTable('{{%spouse}}');
    }
}
