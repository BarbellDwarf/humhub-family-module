<?php

namespace humhub\modules\family\widgets;

use humhub\components\Widget;
use humhub\modules\family\models\Child;
use humhub\modules\user\models\User;
use Yii;

/**
 * Children Profile Widget
 *
 * Displays a list of children on user profiles with management controls.
 *
 * Features:
 * - Shows all children for the profile owner
 * - Displays child name, age, and birthdate
 * - Provides Add/Edit/Delete controls for authorized users
 * - Handles empty state gracefully
 *
 * Display Rules:
 * - Always visible to profile owner (can add children)
 * - Visible to others if profile has children
 * - Only shows on user profiles, not spaces
 * - Edit/Delete controls only for owner or admins
 *
 * @property User $user The profile owner
 */
class ChildrenWidget extends Widget
{
    /**
     * @var User profile owner
     */
    public $user;

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (!$this->user instanceof User) {
            return '';
        }

        $currentUser = Yii::$app->user->identity;
        $canEdit = $currentUser && ($currentUser->id === $this->user->id || Yii::$app->user->isAdmin());

        $supportsChildUserAccount = (new Child())->supportsChildUserAccount();
        $children = Child::find()
            ->where(['user_id' => $this->user->id])
            ->with($supportsChildUserAccount ? ['childUser', 'user'] : ['user'])
            ->orderBy(['birth_date' => SORT_ASC])
            ->all();

        return $this->render('children', [
            'children' => $children,
            'user' => $this->user,
            'canEdit' => $canEdit,
        ]);
    }
}
