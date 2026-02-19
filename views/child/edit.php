<?php
/**
 * Edit Child View
 *
 * Allows authorized users to modify child information.
 *
 * @var $model \humhub\modules\family\models\Child Existing child model instance
 */

use yii\helpers\Html;

$this->title = Yii::t('FamilyModule.base', 'Edit Child: {name}', ['name' => $model->getDisplayName()]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('base', 'Profile'), 'url' => ['/user/profile']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('FamilyModule.base', 'Children'), 'url' => ['/family/child']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <strong><?= Html::encode($this->title) ?></strong>
    </div>
    <div class="panel-body">
        <p class="text-muted"><?= Html::encode(Yii::t('FamilyModule.base', 'Updating the linked account or birth date will affect calendar entries.')) ?></p>
        <?= $this->render('_form', ['model' => $model, 'profileUser' => $model->user]) ?>
    </div>
</div>
