<?php

use humhub\widgets\bootstrap\Button;
use humhub\widgets\form\ActiveForm;
use yii\helpers\Url;

/**
 * @var $model \humhub\modules\family\models\FamilyConfigureForm
 */
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('FamilyModule.base', 'Family Module Configuration'); ?>
    </div>
    <div class="panel-body">
        <p><?= Yii::t('FamilyModule.base', 'Configure optional features for the Family module.'); ?></p>
        <br/>

        <?php $form = ActiveForm::begin(); ?>

        <div class="mb-3">
            <?= $form->field($model, 'enableDiagramTab')->checkbox(); ?>
            <div class="help-block">
                <?= Yii::t('FamilyModule.base', 'Shows the Family Diagram inside the Family profile tab.'); ?>
            </div>
        </div>

        <hr>
        <?= Button::save()->submit() ?>
        <?= Button::light(Yii::t('FamilyModule.base', 'Back to modules'))
            ->link(Url::to(['/admin/module']))
            ->cssClass('float-end') ?>
        <?php $form::end(); ?>
    </div>
</div>
