<?php

use humhub\modules\family\assets\FamilyAsset;
use humhub\modules\family\models\Child;
use yii\helpers\Html;

/**
 * @var $user \humhub\modules\user\models\User
 * @var $spouse \humhub\modules\family\models\Spouse|null
 * @var $children \humhub\modules\family\models\Child[]
 * @var $directGrandchildren \humhub\modules\family\models\Child[]
 * @var $childFamilies array
 */

FamilyAsset::register($this);
?>

<div class="panel panel-default family-diagram">
    <div class="panel-heading">
        <strong><?= Html::encode(Yii::t('FamilyModule.base', 'Family Diagram')) ?></strong>
    </div>
    <div class="panel-body">
        <div class="family-diagram-section">
            <div class="family-diagram-title"><?= Html::encode(Yii::t('FamilyModule.base', 'Household')) ?></div>
            <div class="family-diagram-row">
                <div class="family-node">
                    <div class="family-node-name"><?= Html::encode($user->displayName) ?></div>
                </div>
                <?php if ($spouse): ?>
                    <div class="family-node">
                        <div class="family-node-name">
                            <?php if ($spouse->spouse_user_id && $spouse->spouseUser): ?>
                                <?= Html::a(Html::encode($spouse->getDisplayName()), $spouse->spouseUser->getUrl()) ?>
                            <?php else: ?>
                                <?= Html::encode($spouse->getDisplayName()) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="family-diagram-section">
            <div class="family-diagram-title"><?= Html::encode(Yii::t('FamilyModule.base', 'Children')) ?></div>
            <?php if (empty($children)): ?>
                <p class="text-muted"><?= Html::encode(Yii::t('FamilyModule.base', 'No children added yet.')) ?></p>
            <?php else: ?>
                <div class="family-diagram-row">
                    <?php foreach ($children as $child): ?>
                        <div class="family-node">
                            <div class="family-node-name">
                                <?php if ($child->hasLinkedChildUser()): ?>
                                    <?= Html::a(Html::encode($child->getDisplayName()), $child->childUser->getUrl()) ?>
                                <?php else: ?>
                                    <?= Html::encode($child->getDisplayName()) ?>
                                <?php endif; ?>
                            </div>
                            <?php if ($child->supportsRelationType()): ?>
                                <div class="family-node-meta">
                                    <span class="label label-default"><?= Html::encode($child->getRelationTypeLabel()) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php foreach ($children as $child): ?>
            <?php if (!empty($childFamilies[$child->id]['children'])): ?>
                <?php $family = $childFamilies[$child->id]; ?>
                <div class="family-diagram-section family-diagram-branch">
                    <div class="family-diagram-subtitle">
                        <?= Html::encode(Yii::t('FamilyModule.base', '{name}\'s Family', ['name' => $child->getDisplayName()])) ?>
                    </div>
                    <div class="family-diagram-row">
                        <div class="family-node">
                            <div class="family-node-name">
                                <?= Html::a(Html::encode($family['user']->displayName), $family['user']->getUrl()) ?>
                            </div>
                        </div>
                        <?php if ($family['spouse']): ?>
                            <div class="family-node">
                                <div class="family-node-name">
                                    <?php if ($family['spouse']->spouse_user_id && $family['spouse']->spouseUser): ?>
                                        <?= Html::a(Html::encode($family['spouse']->getDisplayName()), $family['spouse']->spouseUser->getUrl()) ?>
                                    <?php else: ?>
                                        <?= Html::encode($family['spouse']->getDisplayName()) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="family-diagram-row">
                        <?php foreach ($family['children'] as $grandchild): ?>
                            <div class="family-node">
                                <div class="family-node-name">
                                    <?php if ($grandchild->hasLinkedChildUser()): ?>
                                        <?= Html::a(Html::encode($grandchild->getDisplayName()), $grandchild->childUser->getUrl()) ?>
                                    <?php else: ?>
                                        <?= Html::encode($grandchild->getDisplayName()) ?>
                                    <?php endif; ?>
                                </div>
                                <?php if ($grandchild->supportsRelationType()): ?>
                                    <div class="family-node-meta">
                                        <span class="label label-default"><?= Html::encode($grandchild->getRelationTypeLabel()) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if (!empty($directGrandchildren)): ?>
            <div class="family-diagram-section">
                <div class="family-diagram-title"><?= Html::encode(Yii::t('FamilyModule.base', 'Grandchildren')) ?></div>
                <div class="family-diagram-row">
                    <?php foreach ($directGrandchildren as $grandchild): ?>
                        <div class="family-node">
                            <div class="family-node-name">
                                <?php if ($grandchild->hasLinkedChildUser()): ?>
                                    <?= Html::a(Html::encode($grandchild->getDisplayName()), $grandchild->childUser->getUrl()) ?>
                                <?php else: ?>
                                    <?= Html::encode($grandchild->getDisplayName()) ?>
                                <?php endif; ?>
                            </div>
                            <?php if ($grandchild->supportsRelationType()): ?>
                                <div class="family-node-meta">
                                    <span class="label label-default"><?= Html::encode($grandchild->getRelationTypeLabel()) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
