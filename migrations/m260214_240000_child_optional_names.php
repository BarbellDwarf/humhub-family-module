<?php

use humhub\components\Migration;

class m260214_240000_child_optional_names extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('child', 'first_name', $this->string(100)->null());
        $this->alterColumn('child', 'last_name', $this->string(100)->null());
    }

    public function safeDown()
    {
        $this->alterColumn('child', 'first_name', $this->string(100)->notNull());
        $this->alterColumn('child', 'last_name', $this->string(100)->notNull());
    }
}
