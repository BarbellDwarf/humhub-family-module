<?php

namespace humhub\modules\family\controllers;

use humhub\components\Controller;
use humhub\modules\family\models\Spouse;
use humhub\modules\user\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Spouse Controller
 *
 * Handles CRUD operations for spouse records.
 */
class SpouseController extends Controller
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
     * Creates a new Spouse model for the current user.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate($cguid = null)
    {
        /** @var User $currentUser */
        $currentUser = Yii::$app->user->identity;

        if (!$currentUser instanceof User) {
            throw new ForbiddenHttpException('You are not allowed to add a spouse record.');
        }

        $profileUser = $currentUser;
        if ($cguid) {
            $profileUser = User::findOne(['guid' => $cguid]);
            if (!$profileUser) {
                throw new NotFoundHttpException('The requested profile does not exist.');
            }
        }

        if ($profileUser->id !== $currentUser->id && !$currentUser->isSystemAdmin()) {
            throw new ForbiddenHttpException('You are not allowed to add a spouse record for this profile.');
        }
        
        // Check if spouse already exists
        $existing = Spouse::findOne(['user_id' => $profileUser->id]);
        if ($existing) {
            Yii::$app->session->setFlash('error', Yii::t('FamilyModule.base', 'You already have a spouse record. Please edit the existing one.'));
            return $this->redirect(['/family/index/index', 'cguid' => $profileUser->guid]);
        }
        
        $model = new Spouse();
        $model->user_id = $profileUser->id;
        $model->populateRelation('user', $profileUser);

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = $profileUser->id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('FamilyModule.base', 'Spouse added successfully.'));
                return $this->redirect(['/family/index/index', 'cguid' => $profileUser->guid]);
            }
        }

        if ($model->user_id && !$model->user) {
            $model->populateRelation('user', $profileUser);
        }

        return $this->render('create', [
            'model' => $model,
            'profileUser' => $profileUser,
        ]);
    }

    /**
     * Updates an existing Spouse model.
     *
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionEdit($id)
    {
        $model = $this->findModel($id);

        /** @var User $currentUser */
        $currentUser = Yii::$app->user->identity;

        // Check permission
        if ($model->user_id !== $currentUser->id && !$currentUser->isSystemAdmin()) {
            throw new ForbiddenHttpException('You are not allowed to edit this spouse record.');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('FamilyModule.base', 'Spouse updated successfully.'));
            return $this->redirect(['/family/index/index', 'cguid' => $model->user->guid]);
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Spouse model.
     *
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        /** @var User $currentUser */
        $currentUser = Yii::$app->user->identity;

        // Check permission
        if ($model->user_id !== $currentUser->id && !$currentUser->isSystemAdmin()) {
            throw new ForbiddenHttpException('You are not allowed to delete this spouse record.');
        }

        $userGuid = $model->user->guid;
        $model->delete();

        Yii::$app->session->setFlash('success', Yii::t('FamilyModule.base', 'Spouse deleted successfully.'));
        return $this->redirect(['/family/index/index', 'cguid' => $userGuid]);
    }

    /**
     * Finds the Spouse model based on its primary key value.
     *
     * @param int $id
     * @return Spouse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Spouse::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested spouse does not exist.');
    }
}
