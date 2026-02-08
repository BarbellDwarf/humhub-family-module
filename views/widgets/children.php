<?php
/**
 * Children Widget View
 *
 * Displays list of children in profile sidebar panel.
 *
 * Available variables:
 * @var $children \humhub\modules\family\models\Child[] Array of child models
 * @var $user \humhub\modules\user\models\User Profile owner
 * @var $canEdit bool Whether current user can manage children
 */

use humhub\modules\family\models\Child;
use humhub\modules\user\models\User;
use humhub\widgets\PanelMenu;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><?= Html::encode(Yii::t('FamilyModule.base', 'Children')) ?></strong>
        <span class="label label-default"><?= count($children) ?></span>
        <?php if ($canEdit): ?>
            <div class="pull-right"><?= PanelMenu::widget(['id' => 'children-panel']) ?></div>
        <?php endif; ?>
    </div>
    <div class="panel-body">
        <?php if (empty($children)): ?>
            <p class="text-muted"><?= Html::encode(Yii::t('FamilyModule.base', 'No children added yet.')) ?></p>
        <?php else: ?>
            <ul class="media-list">
                <?php foreach ($children as $child): ?>
                    <li class="media">
                        <div class="media-body">
                            <strong><?= Html::encode($child->getDisplayName()) ?></strong>
                            <?php if (($age = $child->getAge()) !== null): ?>
                                <span class="text-muted">(<?= Html::encode($age) ?> <?= Yii::t('FamilyModule.base', 'years old') ?>)</span>
                            <?php endif; ?>
                            <div class="text-muted">
                                <?= Yii::t('FamilyModule.base', 'Birth date: {date}', ['date' => Html::encode($child->birth_date)]) ?>
                            </div>
                            <?php if ($child->mother instanceof User): ?>
                                <div><?= Yii::t('FamilyModule.base', 'Mother: {name}', [
                                        'name' => Html::a(Html::encode($child->mother->displayName), $child->mother->getUrl())
                                    ]) ?></div>
                            <?php endif; ?>
                            <?php if ($child->father instanceof User): ?>
                                <div><?= Yii::t('FamilyModule.base', 'Father: {name}', [
                                        'name' => Html::a(Html::encode($child->father->displayName), $child->father->getUrl())
                                    ]) ?></div>
                            <?php endif; ?>
                        </div>
                        <?php if ($canEdit): ?>
                            <div class="media-right">
                                <a class="btn btn-sm btn-default" aria-label="<?= Html::encode(Yii::t('FamilyModule.base', 'Edit')) ?>"
                                   href="<?= Url::to(['/family/child/edit', 'id' => $child->id]) ?>">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a class="btn btn-sm btn-danger"
                                   aria-label="<?= Html::encode(Yii::t('FamilyModule.base', 'Delete')) ?>"
                                   href="<?= Url::to(['/family/child/delete', 'id' => $child->id]) ?>"
                                   data-confirm="<?= Html::encode(Yii::t('FamilyModule.base', 'Are you sure you want to delete this child?')) ?>"
                                   data-method="post">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <?php if ($canEdit): ?>
        <div class="panel-footer text-center">
            <a class="btn btn-primary"
               href="<?= Url::to(['/family/child/create']) ?>">
                <i class="fa fa-plus"></i> <?= Html::encode(Yii::t('FamilyModule.base', 'Add Child')) ?>
            </a>
        </div>
    <?php endif; ?>
</div>
