<?php

namespace humhub\modules\family\controllers;

use humhub\components\Controller;
use humhub\modules\family\models\Child;
use humhub\modules\user\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Child Controller
 *
 * Handles CRUD operations for child profiles.
 *
 * Actions:
 * - create: Add new child to current user's profile
 * - edit: Modify existing child (owner/admin only)
 * - delete: Remove child (owner/admin only)
 *
 * Access Control:
 * - Users can only manage children on their own profile
 * - Administrators can manage all children
 * - Guests cannot access any actions
 *
 * After successful operations, redirects to profile with flash message.
 */
class ChildController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Creates a new Child model for the current user.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        /** @var User $currentUser */
        $currentUser = Yii::$app->user->identity;
        $model = new Child();
        $model->user_id = $currentUser->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('FamilyModule.base', 'Child added successfully.'));
            return $this->redirect(['/user/profile', 'uguid' => $currentUser->guid]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Child model.
     *
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);
        if (!$this->canEdit($model)) {
            throw new ForbiddenHttpException(Yii::t('FamilyModule.base', 'You are not allowed to edit this child.'));
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('FamilyModule.base', 'Child updated successfully.'));
            return $this->redirect(['/user/profile', 'uguid' => $model->user->guid]);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Child model.
     *
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!$this->canEdit($model)) {
            throw new ForbiddenHttpException(Yii::t('FamilyModule.base', 'You are not allowed to delete this child.'));
        }

        $profileGuid = $model->user->guid;
        $model->delete();
        Yii::$app->session->setFlash('success', Yii::t('FamilyModule.base', 'Child deleted successfully.'));

        return $this->redirect(['/user/profile', 'uguid' => $profileGuid]);
    }

    /**
     * Checks whether current user can edit the model.
     *
     * @param Child $child
     * @return bool
     */
    protected function canEdit(Child $child)
    {
        $currentUser = Yii::$app->user->identity;
        if (!$currentUser instanceof User) {
            return false;
        }

        return $currentUser->id === $child->user_id || Yii::$app->user->isAdmin();
    }

    /**
     * Finds the Child model.
     *
     * @param int $id
     * @return Child
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Child::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('FamilyModule.base', 'The requested child does not exist.'));
    }
}
