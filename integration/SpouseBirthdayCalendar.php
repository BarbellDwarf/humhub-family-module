<?php

namespace humhub\modules\family\integration;

use humhub\modules\calendar\interfaces\event\CalendarItemsEvent;
use humhub\modules\calendar\interfaces\event\CalendarItemTypesEvent;

class SpouseBirthdayCalendar
{
    public const DEFAULT_COLOR = '#FF1493'; // Deep pink for spouse birthdays

    /**
     * Register spouse birthday calendar type
     */
    public static function addItemTypes(CalendarItemTypesEvent $event)
    {
        $event->addType(SpouseBirthdayCalendarType::ITEM_TYPE_KEY, new SpouseBirthdayCalendarType());
    }

    /**
     * Add spouse birthday entries to calendar
     */
    public static function addItems(CalendarItemsEvent $event)
    {
        $query = new SpouseBirthdayCalendarQuery();
        $start = $event->start ? $event->start->format('Y-m-d H:i:s') : null;
        $end = $event->end ? $event->end->format('Y-m-d H:i:s') : null;
        $entries = $query->getEntries($start, $end);

        if (!empty($entries)) {
            $event->addItems(SpouseBirthdayCalendarType::ITEM_TYPE_KEY, $entries);
        }
    }
}
