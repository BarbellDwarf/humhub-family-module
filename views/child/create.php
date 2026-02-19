<?php
/**
 * Create Child View
 *
 * Allows users to add a new child to their profile.
 *
 * @var $model \humhub\modules\family\models\Child New child model instance
 * @var $profileUser \humhub\modules\user\models\User
 */

use yii\helpers\Html;

$this->title = Yii::t('FamilyModule.base', 'Add Child');
$this->params['breadcrumbs'][] = ['label' => Yii::t('base', 'Profile'), 'url' => ['/user/profile']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <strong><?= Html::encode(Yii::t('FamilyModule.base', 'Add Child to Your Profile')) ?></strong>
    </div>
    <div class="panel-body">
        <p class="text-muted"><?= Html::encode(Yii::t('FamilyModule.base', 'Add children with or without linking a HumHub account.')) ?></p>
        <?= $this->render('_form', ['model' => $model, 'profileUser' => $profileUser]) ?>
    </div>
</div>
