<?php
/**
 * Family Module Configuration
 *
 * This file defines:
 * - Event listeners for calendar birthday integration
 * - URL routing rules for child CRUD operations
 * - Profile widget registration
 * - Module namespace and ID
 */

return [
    'id' => 'family',
    'class' => 'humhub\modules\family\Module',
    'namespace' => 'humhub\modules\family',

    // Event listeners
    'events' => [
        // Hook into calendar birthday queries to add children's birthdays
        [
            'class' => 'humhub\modules\calendar\interfaces\CalendarService',
            'event' => 'EVENT_GET_BIRTHDAYS',
            'callback' => ['humhub\modules\family\Events', 'onBirthdayQuery']
        ],

        // Register children widget on user profile sidebar
        [
            'class' => 'humhub\modules\user\widgets\ProfileSidebar',
            'event' => 'init',
            'callback' => ['humhub\modules\family\Events', 'onProfileSidebar']
        ],
    ],

    // URL routing rules
    'urlManagerRules' => [
        'family/child/create' => 'family/child/create',
        'family/child/edit' => 'family/child/edit',
        'family/child/delete' => 'family/child/delete',
    ],
];
