<?php

namespace humhub\modules\family;

use DateTime;
use humhub\modules\family\models\Child;
use humhub\modules\family\widgets\ChildrenWidget;
use humhub\modules\user\models\User;
use Yii;
use yii\base\Event;

/**
 * Event Handlers for Family Module
 *
 * This class contains static methods that respond to HumHub events:
 * 1. Calendar birthday queries - injects children's birthdays
 * 2. Profile sidebar initialization - adds children widget
 */
class Events
{
    /**
     * Handle calendar birthday query event
     *
     * Called by the Calendar module when collecting birthdays to display.
     * This method:
     * 1. Queries all children with birthdays
     * 2. Formats each child's birthday as a calendar entry
     * 3. Links birthday to parent's profile
     * 4. Appends results to the calendar event
     *
     * Birthday Entry Format:
     * - Title: "[Child Name] ([Parent Name]'s child)"
     * - Date: Child's birth_date (recurring annually)
     * - Link: Parent's profile URL
     * - Type: Birthday event type
     *
     * @param Event $event Calendar birthday query event
     * @return void
     */
    public static function onBirthdayQuery($event)
    {
        if (!property_exists($event, 'result')) {
            return;
        }

        $children = Child::find()->with(['user'])->all();
        if (empty($children)) {
            return;
        }

        foreach ($children as $child) {
            if (!$child->user instanceof User || empty($child->birth_date)) {
                continue;
            }

            $title = sprintf('%s (%s\'s child)', $child->getDisplayName(), $child->user->displayName);
            $birthDate = DateTime::createFromFormat('Y-m-d', $child->birth_date);
            if (!$birthDate) {
                continue;
            }

            $event->result[] = [
                'title' => $title,
                'start' => $birthDate->format('Y-m-d'),
                'allDay' => true,
                'editable' => false,
                'url' => $child->user->getUrl(),
                'type' => 'birthday',
                'record' => $child,
            ];
        }
    }

    /**
     * Handle profile sidebar initialization
     *
     * Adds the ChildrenWidget to user profile sidebars.
     * Only displays on user profiles (not spaces).
     *
     * @param Event $event Profile sidebar init event
     * @return void
     */
    public static function onProfileSidebar($event)
    {
        if (!isset($event->sender) || !property_exists($event->sender, 'user')) {
            return;
        }

        $event->sender->addWidget(ChildrenWidget::class, ['user' => $event->sender->user], ['sortOrder' => 300]);
    }
}
