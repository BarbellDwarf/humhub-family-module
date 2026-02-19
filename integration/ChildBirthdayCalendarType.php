<?php

namespace humhub\modules\family\integration;

use Yii;
use humhub\modules\calendar\interfaces\event\CalendarTypeIF;

class ChildBirthdayCalendarType implements CalendarTypeIF
{
    public const ITEM_TYPE_KEY = 'child_birthday';

    public function getKey()
    {
        return self::ITEM_TYPE_KEY;
    }

    public function getTitle()
    {
        return Yii::t('FamilyModule.base', 'Child Birthday');
    }

    public function getDescription()
    {
        return Yii::t('FamilyModule.base', 'Birthdays of family members');
    }

    public function getDefaultColor()
    {
        return ChildBirthdayCalendar::DEFAULT_COLOR;
    }

    public function getIcon()
    {
        return 'fa-birthday-cake';
    }
}
