<?php
/**
 * Child Form Partial
 *
 * Shared form used by both create.php and edit.php views.
 *
 * Available variables:
 * @var $model \humhub\modules\family\models\Child The child model (new or existing)
 * @var $form \humhub\widgets\ActiveForm The form widget instance
 *
 * Form Fields:
 * - first_name: Text input (required)
 * - last_name: Text input (required)
 * - birth_date: Date picker (required, cannot be future date)
 * - mother_id: Dropdown of users (optional, with "Not specified" option)
 * - father_id: Dropdown of users (optional, with "Not specified" option)
 *
 * Note: Mother/Father dropdowns could be all users or filtered by gender
 * depending on your user profile fields setup.
 */

use humhub\modules\user\models\User;
use humhub\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$userQuery = User::find()
    ->select(['id', 'display_name'])
    ->orderBy(['display_name' => SORT_ASC])
    ->limit(500)
    ->asArray()
    ->all();
$users = ArrayHelper::map($userQuery, 'id', 'display_name');
?>

<?php $form = ActiveForm::begin(['id' => 'child-form']); ?>

<?= $form->field($model, 'first_name')
    ->textInput(['maxlength' => true, 'autofocus' => true])
    ->hint(Yii::t('FamilyModule.base', 'Enter the child\'s first name.')) ?>

<?= $form->field($model, 'last_name')
    ->textInput(['maxlength' => true])
    ->hint(Yii::t('FamilyModule.base', 'Enter the child\'s last name.')) ?>

<?= $form->field($model, 'birth_date')
    ->input('date', ['max' => date('Y-m-d')])
    ->hint(Yii::t('FamilyModule.base', 'Birth date cannot be in the future.')) ?>

<?= $form->field($model, 'mother_id')
    ->dropDownList($users, ['prompt' => Yii::t('FamilyModule.base', '-- Not specified --')])
    ->hint(Yii::t('FamilyModule.base', 'Optional mother reference.')) ?>

<?= $form->field($model, 'father_id')
    ->dropDownList($users, ['prompt' => Yii::t('FamilyModule.base', '-- Not specified --')])
    ->hint(Yii::t('FamilyModule.base', 'Optional father reference.')) ?>

<div class="form-group">
    <?= Html::submitButton(Yii::t('FamilyModule.base', 'Save Child'), ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('FamilyModule.base', 'Cancel'), Url::to(['/user/profile', 'uguid' => Yii::$app->user->identity->guid]), ['class' => 'btn btn-default']) ?>
</div>

<?php ActiveForm::end(); ?>
