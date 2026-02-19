<?php

namespace humhub\modules\family\models;

use humhub\modules\family\Module;
use Yii;
use yii\base\Model;

class FamilyConfigureForm extends Model
{
    public $enableDiagramTab = false;

    /** @var Module */
    public $module;

    public function init()
    {
        parent::init();

        $this->module = Yii::$app->getModule('family');
        if ($this->module) {
            $this->enableDiagramTab = (bool)$this->module->settings->get('enableDiagramTab', false);
        }
    }

    public function rules()
    {
        return [
            ['enableDiagramTab', 'boolean'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'enableDiagramTab' => Yii::t('FamilyModule.base', 'Enable Family Diagram section'),
        ];
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        if ($this->module) {
            $this->module->settings->set('enableDiagramTab', (bool)$this->enableDiagramTab);
        }

        return true;
    }
}
