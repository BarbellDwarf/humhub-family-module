<?php

namespace humhub\modules\family\integration;

use Yii;
use yii\base\Component;

/**
 * Child Birthday Calendar Integration
 *
 * Integrates children's birthdays into HumHub's calendar module.
 * Follows the same pattern as the core BirthdayCalendar integration.
 */
class ChildBirthdayCalendar extends Component
{
    /**
     * Default color for child birthday calendar items
     */
    public const DEFAULT_COLOR = '#FFA500';

    public const ITEM_TYPE_KEY = 'child_birthday';

    /**
     * Register child birthday item type with calendar
     *
     * @param \humhub\modules\calendar\interfaces\event\CalendarItemTypesEvent $event
     * @return void
     */
    public static function addItemTypes($event)
    {
        $event->addType(self::ITEM_TYPE_KEY, new ChildBirthdayCalendarType());
    }

    /**
     * Add child birthday calendar items to event
     *
     * @param \humhub\modules\calendar\interfaces\event\CalendarItemsEvent $event
     * @throws \Throwable
     */
    public static function addItems($event)
    {
        $children = ChildBirthdayCalendarQuery::findForEvent($event);

        foreach ($children as $child) {
            $item = new ChildBirthdayCalendarEntry(['model' => $child]);
            $event->addItems(self::ITEM_TYPE_KEY, $item);
        }
    }
}
