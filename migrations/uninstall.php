<?php

use humhub\components\Migration;

/**
 * Uninstall migration for the Family module.
 *
 * Drops the `child` table when uninstalling the module.
 */
class uninstall extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if ($this->db->schema->getTableSchema('child', true) !== null) {
            $this->dropTable('child');
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "uninstall cannot be reverted.\n";
        return false;
    }
}
