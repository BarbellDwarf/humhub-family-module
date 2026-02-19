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

use humhub\modules\family\assets\FamilyAsset;
use humhub\modules\family\models\Child;
use humhub\widgets\PanelMenu;
use yii\helpers\Html;
use yii\helpers\Url;

FamilyAsset::register($this);
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
                            <strong>
                                <?php if ($child->hasLinkedChildUser()): ?>
                                    <?= Html::a(Html::encode($child->getDisplayName()), $child->childUser->getUrl()) ?>
                                <?php else: ?>
                                    <?= Html::encode($child->getDisplayName()) ?>
                                <?php endif; ?>
                            </strong>
                            <?php if ($child->supportsRelationType()): ?>
                                <span class="label label-default"><?= Html::encode($child->getRelationTypeLabel()) ?></span>
                            <?php endif; ?>
                            <?php if (($age = $child->getAge()) !== null): ?>
                                <span class="text-muted">(<?= Html::encode($age) ?> <?= Yii::t('FamilyModule.base', 'years old') ?>)</span>
                            <?php endif; ?>
                            <div class="text-muted">
                                <?= Yii::t('FamilyModule.base', 'Birth date: {date}', ['date' => Html::encode($child->getEffectiveBirthDate() ?: '-')]) ?>
                            </div>
                        </div>
                        <?php if ($canEdit): ?>
                            <div class="media-right">
                                <a class="btn btn-sm btn-default family-action-btn" aria-label="<?= Html::encode(Yii::t('FamilyModule.base', 'Edit')) ?>"
                                   href="<?= Url::to(['/family/child/edit', 'id' => $child->id]) ?>">
                                    <i class="fa fa-pencil"></i>
                                </a>
                                <a class="btn btn-sm btn-danger family-action-btn"
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
               href="<?= Url::to(['/family/child/create', 'cguid' => $user->guid]) ?>">
                <i class="fa fa-plus"></i> <?= Html::encode(Yii::t('FamilyModule.base', 'Add Child')) ?>
            </a>
        </div>
    <?php endif; ?>
</div>
