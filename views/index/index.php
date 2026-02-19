<?php
use humhub\modules\family\assets\FamilyAsset;
use humhub\modules\family\models\Child;
use humhub\modules\family\models\Spouse;
use yii\helpers\Html;

$this->title = Yii::t('FamilyModule.base', 'Family Members');
FamilyAsset::register($this);
?>

<!-- Spouse Section -->
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><?= Html::encode(Yii::t('FamilyModule.base', 'Spouse')) ?></strong>
        <?php if ($canEdit && !$spouse): ?>
            <div class="pull-right">
                <?= Html::a(
                    '<i class="fa fa-plus"></i> ' . Yii::t('FamilyModule.base', 'Add Spouse'),
                    ['/family/spouse/create', 'cguid' => $user->guid],
                    ['class' => 'btn btn-sm btn-primary']
                ) ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="panel-body">
        <?php if (!$spouse): ?>
            <p class="text-muted text-center" style="padding: 20px;">
                <i class="fa fa-heart-o fa-2x" style="color: #ccc;"></i><br><br>
                <?= Yii::t('FamilyModule.base', 'No spouse information added.') ?>
            </p>
        <?php else: ?>
            <table class="table">
                <tr>
                    <th style="width: 150px;"><?= Yii::t('FamilyModule.base', 'Name') ?></th>
                    <td>
                        <?php if ($spouse->spouse_user_id && $spouse->spouseUser): ?>
                            <?= Html::a(Html::encode($spouse->getDisplayName()), $spouse->spouseUser->getUrl()) ?>
                        <?php else: ?>
                            <?= Html::encode($spouse->getDisplayName()) ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php if ($spouse->getEffectiveBirthDate()): ?>
                <tr>
                    <th><?= Yii::t('FamilyModule.base', 'Birth Date') ?></th>
                    <td><?= Html::encode($spouse->getEffectiveBirthDate()) ?></td>
                </tr>
                <?php endif; ?>
                <?php if ($spouse->getDisplayEmail()): ?>
                <tr>
                    <th><?= Yii::t('FamilyModule.base', 'Email') ?></th>
                    <td><?= Html::encode($spouse->getDisplayEmail()) ?></td>
                </tr>
                <?php endif; ?>
                <?php if ($spouse->phone): ?>
                <tr>
                    <th><?= Yii::t('FamilyModule.base', 'Phone') ?></th>
                    <td><?= Html::encode($spouse->phone) ?></td>
                </tr>
                <?php endif; ?>
                <?php if ($canEdit): ?>
                <tr>
                    <th><?= Yii::t('FamilyModule.base', 'Actions') ?></th>
                    <td>
                        <?= Html::a('<i class="fa fa-pencil"></i> ' . Yii::t('FamilyModule.base', 'Edit'), ['/family/spouse/edit', 'id' => $spouse->id], ['class' => 'btn btn-sm btn-default family-action-btn']) ?>
                        <?= Html::a('<i class="fa fa-trash"></i> ' . Yii::t('FamilyModule.base', 'Delete'), ['/family/spouse/delete', 'id' => $spouse->id], [
                            'class' => 'btn btn-sm btn-danger family-action-btn',
                            'data-confirm' => Yii::t('FamilyModule.base', 'Are you sure?'),
                            'data-method' => 'post'
                        ]) ?>
                    </td>
                </tr>
                <?php endif; ?>
            </table>
        <?php endif; ?>
    </div>
</div>

<!-- Children Section -->
<div class="panel panel-default">
    <div class="panel-heading">
        <strong><?= Html::encode(Yii::t('FamilyModule.base', 'Children')) ?></strong>
        <?php if ($canEdit): ?>
            <div class="pull-right">
                <?= Html::a(
                    '<i class="fa fa-plus"></i> ' . Yii::t('FamilyModule.base', 'Add Child'),
                    ['/family/child/create', 'cguid' => $user->guid],
                    ['class' => 'btn btn-sm btn-primary']
                ) ?>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="panel-body">
        <?php if (empty($children)): ?>
            <p class="text-muted text-center" style="padding: 40px;">
                <i class="fa fa-users fa-3x" style="color: #ccc;"></i><br><br>
                <?= Yii::t('FamilyModule.base', 'No children added yet.') ?>
            </p>
        <?php else: ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><?= Yii::t('FamilyModule.base', 'Name') ?></th>
                        <th><?= Yii::t('FamilyModule.base', 'Birth Date') ?></th>
                        <th><?= Yii::t('FamilyModule.base', 'Age') ?></th>
                        <?php if ($canEdit): ?>
                            <th><?= Yii::t('FamilyModule.base', 'Actions') ?></th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($children as $child): ?>
                        <tr>
                            <td>
                                <?php if ($child->hasLinkedChildUser()): ?>
                                    <strong><?= Html::a(Html::encode($child->getDisplayName()), $child->childUser->getUrl()) ?></strong>
                                <?php else: ?>
                                    <strong><?= Html::encode($child->getDisplayName()) ?></strong>
                                <?php endif; ?>
                                <?php if ($child->supportsRelationType()): ?>
                                    <span class="label label-default"><?= Html::encode($child->getRelationTypeLabel()) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= ($birthDate = $child->getEffectiveBirthDate()) ? Html::encode($birthDate) : '-' ?></td>
                            <td><?= ($age = $child->getAge()) !== null ? Html::encode($age) : '-' ?></td>
                            <?php if ($canEdit): ?>
                                <td>
                                    <?= Html::a('<i class="fa fa-pencil"></i>', ['/family/child/edit', 'id' => $child->id], ['class' => 'btn btn-sm btn-default family-action-btn']) ?>
                                    <?= Html::a('<i class="fa fa-trash"></i>', ['/family/child/delete', 'id' => $child->id], [
                                        'class' => 'btn btn-sm btn-danger family-action-btn',
                                        'data-confirm' => Yii::t('FamilyModule.base', 'Are you sure?'),
                                        'data-method' => 'post'
                                    ]) ?>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php if ($showDiagram): ?>
    <?= $this->render('diagram', [
        'user' => $user,
        'spouse' => $spouse,
        'children' => $diagramChildren,
        'directGrandchildren' => $diagramGrandchildren,
        'childFamilies' => $diagramFamilies,
    ]) ?>
<?php endif; ?>
