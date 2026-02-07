<?php

namespace humhub\modules\family\permissions;

use humhub\libs\BasePermission;
use humhub\modules\user\models\User;

/**
 * ManageFamily Permission
 *
 * Controls who can manage family/children profiles.
 *
 * Default: All users can manage their own children.
 * Can be restricted via group permissions in future versions.
 *
 * Potential future use cases:
 * - Restrict feature to certain user groups
 * - Allow space-level family management
 * - Enable/disable per-profile basis
 */
class ManageFamily extends BasePermission
{
    /**
     * @inheritdoc
     */
    public $defaultState = self::STATE_ALLOW;

    /**
     * @inheritdoc
     */
    protected $fixedGroups = [
        User::USERGROUP_USER,
    ];

    /**
     * @inheritdoc
     */
    protected $title = 'Manage Family';

    /**
     * @inheritdoc
     */
    protected $description = 'Allows users to add and manage children on their profiles';
}
