<?php

namespace humhub\modules\family;

use Yii;
use humhub\components\Module as BaseModule;

/**
 * Family Management Module
 *
 * Allows users to add children to their profiles without creating accounts,
 * with automatic birthday calendar integration.
 *
 * @package humhub\modules\family
 */
class Module extends BaseModule
{
    public const VERSION = '1.0.0';

    /**
     * @inheritdoc
     */
    public function getConfigUrl()
    {
        return null;
    }

    /**
     * Runs module migrations automatically on install.
     *
     * @return bool whether installation succeeded
     */
    public function install()
    {
        try {
            if (!parent::install()) {
                return false;
            }

            // Attempt to run pending migrations if module manager supports it.
            if (Yii::$app->has('moduleManager') && method_exists(Yii::$app->moduleManager, 'migrateModule')) {
                Yii::$app->moduleManager->migrateModule($this->id);
            }

            return true;
        } catch (\Throwable $e) {
            Yii::error('Family module install failed: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }

    /**
     * Performs cleanup tasks on disable.
     *
     * @return bool whether disabling succeeded
     */
    public function disable()
    {
        try {
            return parent::disable();
        } catch (\Throwable $e) {
            Yii::error('Family module disable failed: ' . $e->getMessage(), __METHOD__);
            return false;
        }
    }
}
