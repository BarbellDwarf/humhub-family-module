<?php

namespace humhub\modules\family\integration;

use DateTime;
use humhub\modules\family\models\Child;
use humhub\modules\calendar\interfaces\event\CalendarItemsEvent;

/**
 * Child Birthday Calendar Query
 *
 * Queries children whose birthdays fall within the requested date range.
 */
class ChildBirthdayCalendarQuery
{
    /**
     * Find children with birthdays in the event's date range
     *
     * @param CalendarItemsEvent $event
     * @return Child[]
     */
    public static function findForEvent(CalendarItemsEvent $event)
    {
        $start = $event->start;
        $end = $event->end;

        if (!$start || !$end) {
            return [];
        }

        $supportsChildUserAccount = (new Child())->supportsChildUserAccount();

        // Get all children with birth dates or linked accounts
        $childQuery = Child::find()
            ->with($supportsChildUserAccount ? ['user', 'childUser', 'childUser.profile'] : ['user']);

        if ($supportsChildUserAccount) {
            $childQuery->where(['or',
                ['not', ['birth_date' => null]],
                ['not', ['child_user_id' => null]]
            ]);
        } else {
            $childQuery->where(['not', ['birth_date' => null]]);
        }

        $children = $childQuery->all();

        $result = [];

        foreach ($children as $child) {
            if ($child->hasLinkedChildUser() && $child->childUser && $child->childUser->profile
                && !empty($child->childUser->profile->birthday)) {
                // Avoid duplicate calendar entries when linked child already has a user birthday.
                continue;
            }

            $effectiveBirthDate = $child->getEffectiveBirthDate();
            if (empty($effectiveBirthDate) || !$child->user) {
                continue;
            }

            $birthDate = DateTime::createFromFormat('Y-m-d', $effectiveBirthDate);
            if (!$birthDate) {
                continue;
            }

            // Check if birthday occurs in the requested range
            // We need to check for the birthday in any year that falls in the range
            $startYear = (int)$start->format('Y');
            $endYear = (int)$end->format('Y');

            for ($year = $startYear; $year <= $endYear; $year++) {
                $birthdayThisYear = DateTime::createFromFormat(
                    'Y-m-d',
                    $year . '-' . $birthDate->format('m-d')
                );

                if ($birthdayThisYear >= $start && $birthdayThisYear <= $end) {
                    $result[] = $child;
                    break;
                }
            }
        }

        return $result;
    }
}
