<?php

namespace humhub\modules\family\integration;

use DateTime;
use humhub\modules\calendar\interfaces\event\AbstractCalendarQuery;
use humhub\modules\family\models\Spouse;

class SpouseBirthdayCalendarQuery extends AbstractCalendarQuery
{
    public function getEntries($start = null, $end = null)
    {
        $entries = [];

        // Get all spouses with birthdays
        $spouses = Spouse::find()
            ->with(['user', 'spouseUser.profile'])
            ->all();

        if (empty($spouses)) {
            return $entries;
        }

        // Parse date range
        $startDate = $start ? DateTime::createFromFormat('Y-m-d H:i:s', $start) : new DateTime();
        $endDate = $end ? DateTime::createFromFormat('Y-m-d H:i:s', $end) : new DateTime('+1 year');

        // For each spouse with a birthday
        foreach ($spouses as $spouse) {
            $birthDate = $spouse->getEffectiveBirthDate();
            if (!$birthDate) {
                continue;
            }

            $birthDateTime = DateTime::createFromFormat('Y-m-d', $birthDate);
            if (!$birthDateTime) {
                continue;
            }

            // Check if birthday falls within the requested range in any year
            $startYear = (int)$startDate->format('Y');
            $endYear = (int)$endDate->format('Y');

            for ($year = $startYear; $year <= $endYear; $year++) {
                $thisYearBirthday = DateTime::createFromFormat(
                    'Y-m-d',
                    $year . '-' . $birthDateTime->format('m-d')
                );

                if ($thisYearBirthday >= $startDate && $thisYearBirthday <= $endDate) {
                    $entries[] = new SpouseBirthdayCalendarEntry(['model' => $spouse]);
                    break;
                }
            }
        }

        return $entries;
    }
}
