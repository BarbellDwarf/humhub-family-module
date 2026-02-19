<?php
/**
 * Spouse Edit View
 */

use yii\helpers\Html;

$this->pageTitle = Yii::t('FamilyModule.base', 'Edit Spouse');
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Html::encode($this->pageTitle) ?>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
            'profileUser' => $model->user,
        ]) ?>
    </div>
</div>
