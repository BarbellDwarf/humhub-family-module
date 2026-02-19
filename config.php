<?php
/**
 * Family Module Configuration
 */

use humhub\modules\user\widgets\ProfileMenu;

return [
    'id' => 'family',
    'class' => 'humhub\modules\family\Module',
    'namespace' => 'humhub\modules\family',

    // Event listeners
    'events' => [
        // Hook into calendar to register children birthday type
        [
            'class' => 'humhub\modules\calendar\interfaces\CalendarService',
            'event' => 'getItemTypes',
            'callback' => ['humhub\modules\family\Events', 'onGetCalendarItemTypes']
        ],

        // Hook into calendar to add children's birthdays
        [
            'class' => 'humhub\modules\calendar\interfaces\CalendarService',
            'event' => 'findItems',
            'callback' => ['humhub\modules\family\Events', 'onFindCalendarItems']
        ],

        // Register Family menu item in profile menu
        [
            'class' => ProfileMenu::class,
            'event' => ProfileMenu::EVENT_INIT,
            'callback' => ['humhub\modules\family\Events', 'onProfileMenuInit']
        ],
    ],

    // URL routing rules
    'urlManagerRules' => [
        'family/child/create' => 'family/child/create',
        'family/child/edit' => 'family/child/edit',
        'family/child/delete' => 'family/child/delete',
    ],
];
