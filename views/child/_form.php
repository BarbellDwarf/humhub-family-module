<?php
/**
 * Child Form Partial
 *
 * Shared form used by both create.php and edit.php views.
 */

use humhub\modules\family\assets\FamilyAsset;
use humhub\modules\user\widgets\UserPickerField;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['id' => 'child-form']); ?>
<?php FamilyAsset::register($this); ?>
<?php $supportsChildUserAccount = $model->supportsChildUserAccount(); ?>

<?php if ($supportsChildUserAccount): ?>
    <div class="alert alert-info">
        <strong><?= Yii::t('FamilyModule.base', 'Option 1: Link to User Account') ?></strong><br>
        <?= Yii::t('FamilyModule.base', 'Select a user if your child has a HumHub account. Their name and birthday will sync from their profile.') ?>
    </div>

    <?= UserPickerField::widget([
        'model' => $model,
        'attribute' => 'child_user_guid',
        'maxSelection' => 1,
        'placeholder' => Yii::t('FamilyModule.base', 'Select child user account (optional)'),
    ]) ?>

    <div class="alert alert-info" style="margin-top: 20px;">
        <strong><?= Yii::t('FamilyModule.base', 'Option 2: Enter Child Details Manually') ?></strong><br>
        <?= Yii::t('FamilyModule.base', 'If your child does not have an account, enter their details below. Leave blank if you selected a user account above.') ?>
    </div>
<?php endif; ?>

<?= $form->field($model, 'first_name')
    ->textInput(['maxlength' => true, 'autofocus' => true])
    ->hint(Yii::t('FamilyModule.base', $supportsChildUserAccount
        ? 'Required only if no user account selected.'
        : 'Enter the child\'s first name.')) ?>

<?= $form->field($model, 'last_name')
    ->textInput(['maxlength' => true])
    ->hint(Yii::t('FamilyModule.base', $supportsChildUserAccount
        ? 'Required only if no user account selected.'
        : 'Enter the child\'s last name.')) ?>

<?= $form->field($model, 'birth_date')
    ->input('date', ['max' => date('Y-m-d')])
    ->hint(Yii::t('FamilyModule.base', $supportsChildUserAccount
        ? 'Required only if no user account selected. Birth date cannot be in the future.'
        : 'Birth date cannot be in the future.')) ?>

<?php if (!$model->supportsRelationType()): ?>
    <div class="alert alert-warning" style="margin-top: 10px;">
        <?= Yii::t('FamilyModule.base', 'Relation types will be available after applying the latest Family module database migration.') ?>
    </div>
<?php endif; ?>

<?php
$profileGuid = null;
if (isset($profileUser) && $profileUser) {
    $profileGuid = $profileUser->guid;
} elseif ($model->user) {
    $profileGuid = $model->user->guid;
} elseif (Yii::$app->user->identity) {
    $profileGuid = Yii::$app->user->identity->guid;
}
?>

<?php if ($model->supportsRelationType()): ?>
    <?= $form->field($model, 'relation_type')
        ->dropDownList($model::getRelationTypeOptions())
        ->hint(Yii::t('FamilyModule.base', 'Select how this person is related to you.')) ?>
<?php endif; ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('FamilyModule.base', 'Save Child'), ['class' => 'btn btn-primary']) ?>
    <?= Html::a(
        Yii::t('FamilyModule.base', 'Cancel'),
        Url::to(['/family/index/index', 'cguid' => $profileGuid ?: Yii::$app->user->identity->guid]),
        ['class' => 'btn btn-default family-action-btn']
    ) ?>
</div>

<?php ActiveForm::end(); ?>
