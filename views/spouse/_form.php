<?php
/**
 * Spouse Form Partial
 *
 * Shared form used by both create.php and edit.php views.
 */

use humhub\modules\user\widgets\UserPickerField;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin(['id' => 'spouse-form']); ?>

<div class="alert alert-info">
    <strong><?= Yii::t('FamilyModule.base', 'Option 1: Link to User Account') ?></strong><br>
    <?= Yii::t('FamilyModule.base', 'Select a user if your spouse has a HumHub account. Their information will automatically sync from their profile.') ?>
</div>

<?= UserPickerField::widget([
    'model' => $model,
    'attribute' => 'spouse_user_guid',
    'maxSelection' => 1,
    'placeholder' => Yii::t('FamilyModule.base', 'Select spouse user account (optional)'),
]) ?>

<div class="alert alert-info" style="margin-top: 20px;">
    <strong><?= Yii::t('FamilyModule.base', 'Option 2: Enter Spouse Details Manually') ?></strong><br>
    <?= Yii::t('FamilyModule.base', 'If your spouse does not have an account, enter their details below. Leave blank if you selected a user account above.') ?>
</div>

<?= $form->field($model, 'first_name')
    ->textInput(['maxlength' => true])
    ->hint(Yii::t('FamilyModule.base', 'Required only if no user account selected.')) ?>

<?= $form->field($model, 'last_name')
    ->textInput(['maxlength' => true])
    ->hint(Yii::t('FamilyModule.base', 'Required only if no user account selected.')) ?>

<?= $form->field($model, 'birth_date')
    ->input('date', ['max' => date('Y-m-d')])
    ->hint(Yii::t('FamilyModule.base', 'Optional. Used for calendar birthday entries. (Not needed if user account selected - will use their profile birthday)')) ?>

<?= $form->field($model, 'email')
    ->textInput(['maxlength' => true, 'type' => 'email'])
    ->hint(Yii::t('FamilyModule.base', 'Optional. (Not needed if user account selected - will use their profile email)')) ?>

<?= $form->field($model, 'phone')
    ->textInput(['maxlength' => true, 'type' => 'tel'])
    ->hint(Yii::t('FamilyModule.base', 'Optional contact phone number.')) ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('FamilyModule.base', 'Save Spouse'), ['class' => 'btn btn-primary']) ?>
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
    <?= Html::a(
        Yii::t('FamilyModule.base', 'Cancel'),
        Url::to(['/family/index/index', 'cguid' => $profileGuid ?: Yii::$app->user->identity->guid]),
        ['class' => 'btn btn-default']
    ) ?>
</div>

<?php ActiveForm::end(); ?>
