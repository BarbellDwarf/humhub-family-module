<?php

namespace humhub\modules\family\integration;

use DateTime;
use humhub\modules\family\models\Child;
use humhub\modules\calendar\interfaces\fullcalendar\FullCalendarEventIF;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

class ChildBirthdayCalendarEntry extends Model implements FullCalendarEventIF
{
    public $model;

    public function __construct($config = [])
    {
        if (isset($config['model'])) {
            $this->model = $config['model'];
        }
        parent::__construct($config);
    }

    public function getUid()
    {
        return 'child_birthday_' . $this->model->id;
    }

    public function getEventType()
    {
        return new ChildBirthdayCalendarType();
    }

    public function isAllDay()
    {
        return true;
    }

    public function getTitle()
    {
        return Html::encode($this->model->getDisplayName()) . "'s Birthday";
    }

    public function getStartDateTime()
    {
        $birthDateValue = $this->model->getEffectiveBirthDate();
        $birthDate = DateTime::createFromFormat('Y-m-d', $birthDateValue);
        if (!$birthDate) {
            return new DateTime();
        }

        $currentYear = (int)date('Y');
        return DateTime::createFromFormat('Y-m-d H:i:s', $currentYear . '-' . $birthDate->format('m-d') . ' 00:00:00');
    }

    public function getEndDateTime()
    {
        return $this->getStartDateTime();
    }

    public function getTimezone()
    {
        return Yii::$app->timeZone;
    }

    public function getEndTimezone()
    {
        return $this->getTimezone();
    }

    public function getUrl()
    {
        return $this->model->user->getUrl();
    }

    public function getColor()
    {
        return ChildBirthdayCalendar::DEFAULT_COLOR;
    }

    public function getLocation()
    {
        return null;
    }

    public function getDescription()
    {
        $birthDateValue = $this->model->getEffectiveBirthDate();
        $birthDate = DateTime::createFromFormat('Y-m-d', $birthDateValue);
        if (!$birthDate) {
            return '';
        }
        $age = (int)date('Y') - (int)$birthDate->format('Y');
        return Yii::t('FamilyModule.base', 'Turns {age} years old', ['age' => $age]);
    }

    public function getBadge()
    {
        return null;
    }

    public function getCalendarOptions()
    {
        return [];
    }

    public function getLastModified()
    {
        return $this->model->updated_at ? new DateTime('@' . $this->model->updated_at) : null;
    }

    public function getSequence()
    {
        return 0;
    }

    // FullCalendarEventIF methods

    public function isUpdatable()
    {
        return false; // Birthdays cannot be dragged/dropped in calendar
    }

    public function updateTime(DateTime $start, DateTime $end)
    {
        return false; // Not updatable
    }

    public function getCalendarViewUrl()
    {
        return $this->model->user->getUrl();
    }

    public function getCalendarViewMode()
    {
        return self::VIEW_MODE_REDIRECT;
    }

    public function getFullCalendarOptions()
    {
        return [];
    }
}
