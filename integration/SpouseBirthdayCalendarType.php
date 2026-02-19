<?php

namespace humhub\modules\family\integration;

use Yii;
use humhub\modules\calendar\interfaces\event\CalendarTypeIF;

class SpouseBirthdayCalendarType implements CalendarTypeIF
{
    public const ITEM_TYPE_KEY = 'spouse_birthday';

    public function getKey()
    {
        return self::ITEM_TYPE_KEY;
    }

    public function getTitle()
    {
        return Yii::t('FamilyModule.base', 'Spouse Birthday');
    }

    public function getDescription()
    {
        return Yii::t('FamilyModule.base', 'Birthdays of spouses');
    }

    public function getDefaultColor()
    {
        return SpouseBirthdayCalendar::DEFAULT_COLOR;
    }

    public function getIcon()
    {
        return 'fa-heart';
    }
}
