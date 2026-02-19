<?php

namespace humhub\modules\family\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\family\models\FamilyConfigureForm;
use Yii;

class ConfigController extends Controller
{
    public function actionIndex()
    {
        $form = new FamilyConfigureForm();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            $this->view->saved();
            return $this->redirect(['/family/config']);
        }

        return $this->render('index', ['model' => $form]);
    }
}
