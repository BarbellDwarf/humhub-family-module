<?php

namespace humhub\modules\family;

use humhub\modules\family\integration\ChildBirthdayCalendar;
use humhub\modules\family\integration\SpouseBirthdayCalendar;
use Yii;
use yii\base\Event;

/**
 * Event Handlers for Family Module
 */
class Events
{
    /**
     * Register calendar item types (children and spouse birthdays)
     */
    public static function onGetCalendarItemTypes($event)
    {
        try {
            ChildBirthdayCalendar::addItemTypes($event);
            SpouseBirthdayCalendar::addItemTypes($event);
        } catch (\Throwable $e) {
            Yii::error('Family module: Failed to add calendar item types - ' . $e->getMessage(), __METHOD__);
        }
    }

    /**
     * Add family birthdays to calendar (children and spouse)
     */
    public static function onFindCalendarItems($event)
    {
        try {
            ChildBirthdayCalendar::addItems($event);
            SpouseBirthdayCalendar::addItems($event);
        } catch (\Throwable $e) {
            Yii::error('Family module: Failed to add calendar items - ' . $e->getMessage(), __METHOD__);
        }
    }

    /**
     * Handle profile menu initialization
     *
     * Adds the Family menu item to user profile navigation.
     */
    public static function onProfileMenuInit($event)
    {
        try {
            $user = $event->sender->user;
            if (!$user) {
                return;
            }

            $event->sender->addItem([
                'label' => Yii::t('FamilyModule.base', 'Family'),
                'url' => ['/family/index/index', 'cguid' => $user->guid],
                'icon' => '<i class="fa fa-users"></i>',
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'family'),
                'sortOrder' => 400,
            ]);

        } catch (\Throwable $e) {
            Yii::error('Family module: Failed to add profile menu item - ' . $e->getMessage(), __METHOD__);
        }
    }
}
