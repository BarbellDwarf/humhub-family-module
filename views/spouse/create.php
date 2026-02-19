<?php
/**
 * Spouse Create View
 */

use yii\helpers\Html;

$this->pageTitle = Yii::t('FamilyModule.base', 'Add Spouse');
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Html::encode($this->pageTitle) ?>
    </div>
    <div class="panel-body">
        <?= $this->render('_form', [
            'model' => $model,
            'profileUser' => $profileUser,
        ]) ?>
    </div>
</div>
