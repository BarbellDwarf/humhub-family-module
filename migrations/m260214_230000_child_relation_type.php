<?php

use humhub\components\Migration;

class m260214_230000_child_relation_type extends Migration
{
    public function safeUp()
    {
        $this->addColumn('child', 'relation_type', $this->string(32)->notNull()->defaultValue('child'));
    }

    public function safeDown()
    {
        $this->dropColumn('child', 'relation_type');
    }
}
