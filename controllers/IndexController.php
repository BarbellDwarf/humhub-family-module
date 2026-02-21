<?php

namespace humhub\modules\family\controllers;

use humhub\modules\content\components\ContentContainerController;
use humhub\modules\family\models\Child;
use humhub\modules\family\models\Spouse;
use humhub\modules\user\models\User;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Index Controller
 *
 * Shows family member list for a user profile
 */
class IndexController extends ContentContainerController
{
    /**
     * @inheritdoc
     */
    public $validContentContainerClasses = [User::class];

    /**
     * Shows list of family members for the profile
     */
    public function actionIndex()
    {
        if (!$this->contentContainer instanceof User) {
            throw new NotFoundHttpException(Yii::t('FamilyModule.base', 'User not found'));
        }

        $user = $this->contentContainer;

        $currentUser = Yii::$app->user->identity;
        $canEdit = $currentUser && ($currentUser->id === $user->id || Yii::$app->user->isAdmin());

        $spouse = $this->getSpouse($user);

        $children = $this->getFamilyChildren($user, $spouse);

        $showDiagram = $this->module && $this->module->settings->get('enableDiagramTab', false);
        $diagramChildren = [];
        $diagramGrandchildren = [];
        $diagramFamilies = [];
        if ($showDiagram) {
            $diagramData = $this->buildDiagramData($user, $spouse, $children);
            $diagramChildren = $diagramData['children'];
            $diagramGrandchildren = $diagramData['grandchildren'];
            $diagramFamilies = $diagramData['families'];
        }

        return $this->render('index', [
            'user' => $user,
            'spouse' => $spouse,
            'children' => $children,
            'canEdit' => $canEdit,
            'showDiagram' => $showDiagram,
            'diagramChildren' => $diagramChildren,
            'diagramGrandchildren' => $diagramGrandchildren,
            'diagramFamilies' => $diagramFamilies,
        ]);
    }

    protected function getSpouse(User $user): ?Spouse
    {
        return Spouse::find()
            ->where(['user_id' => $user->id])
            ->with(['spouseUser'])
            ->one();
    }

    protected function getFamilyChildren(User $user, ?Spouse $spouse, ?array $relationTypes = null): array
    {
        $supportsChildUserAccount = (new Child())->supportsChildUserAccount();
        $supportsRelationType = (new Child())->supportsRelationType();
        $childQuery = Child::find()
            ->where(['user_id' => $user->id])
            ->with($supportsChildUserAccount ? ['childUser', 'childUser.profile', 'user'] : ['user']);

        if ($spouse && $spouse->spouse_user_id) {
            $childQuery->orWhere(['user_id' => $spouse->spouse_user_id]);
        }

        if ($relationTypes !== null && $supportsRelationType) {
            $childQuery->andWhere(['relation_type' => $relationTypes]);
        }

        $children = $childQuery
            ->orderBy(['birth_date' => SORT_ASC])
            ->all();

        $uniqueChildren = [];
        foreach ($children as $child) {
            if (!isset($uniqueChildren[$child->id])) {
                $uniqueChildren[$child->id] = $child;
            }
        }

        return array_values($uniqueChildren);
    }

    protected function getChildFamilies(array $children): array
    {
        $childFamilies = [];
        $supportsRelationType = (new Child())->supportsRelationType();
        $linkedChildUsers = [];
        foreach ($children as $child) {
            if ($child->hasLinkedChildUser()) {
                $linkedChildUsers[$child->child_user_id] = $child->id;
            }
        }

        $processedPairs = [];
        foreach ($children as $child) {
            if (!$child->hasLinkedChildUser()) {
                continue;
            }

            $childUser = $child->childUser;
            if (!$childUser instanceof User) {
                continue;
            }

            $childSpouse = $this->getSpouse($childUser);
            if ($childSpouse && $childSpouse->spouse_user_id
                && isset($linkedChildUsers[$childSpouse->spouse_user_id])) {
                $pairKey = $this->getFamilyPairKey($childUser->id, $childSpouse->spouse_user_id);
                if (isset($processedPairs[$pairKey])) {
                    continue;
                }
                $processedPairs[$pairKey] = true;
            }

            $grandchildren = $this->getFamilyChildren(
                $childUser,
                $childSpouse,
                $supportsRelationType ? Child::getPrimaryRelationTypes() : null
            );

            $childFamilies[$child->id] = [
                'user' => $childUser,
                'spouse' => $childSpouse,
                'children' => $grandchildren,
            ];
        }

        return $childFamilies;
    }

    protected function getFamilyPairKey(int $userId, int $spouseUserId): string
    {
        $first = min($userId, $spouseUserId);
        $second = max($userId, $spouseUserId);
        return $first . ':' . $second;
    }

    protected function buildDiagramData(User $user, ?Spouse $spouse, array $children): array
    {
        $supportsRelationType = (new Child())->supportsRelationType();

        $primaryChildren = [];
        $directGrandchildren = [];

        if ($supportsRelationType) {
            foreach ($children as $child) {
                if ($child->relation_type === Child::RELATION_TYPE_GRANDCHILD) {
                    $directGrandchildren[] = $child;
                } else {
                    $primaryChildren[] = $child;
                }
            }
        } else {
            $primaryChildren = $children;
        }

        $childFamilies = $this->getChildFamilies($primaryChildren);

        return [
            'children' => $primaryChildren,
            'grandchildren' => $directGrandchildren,
            'families' => $childFamilies,
        ];
    }
}
