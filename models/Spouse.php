<?php

namespace humhub\modules\family\models;

use DateTime;
use Yii;
use humhub\modules\user\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Spouse extends ActiveRecord
{
    public $spouse_user_guid;

    public static function tableName()
    {
        return 'spouse';
    }

    public function behaviors()
    {
        return [TimestampBehavior::class];
    }

    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'spouse_user_id'], 'integer'],
            [['birth_date'], 'date', 'format' => 'php:Y-m-d'],
            [['first_name', 'last_name'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
            [['spouse_user_guid'], 'safe'],
            
            // Only require name fields if NO spouse user is selected
            [['first_name', 'last_name'], 'required', 
                'when' => function($model) {
                    // Check if spouse_user_guid OR spouse_user_id has a value
                    $hasGuid = !empty($model->spouse_user_guid) && $model->spouse_user_guid !== '[]';
                    $hasId = !empty($model->spouse_user_id);
                    return !$hasGuid && !$hasId;
                },
                'whenClient' => "function (attribute, value) {
                    var spouseGuid = $('#spouse-spouse_user_guid').val();
                    return !spouseGuid || spouseGuid === '' || spouseGuid === '[]';
                }",
                'message' => 'Either select a user account or enter name fields.'
            ],
            
            [['user_id'], 'unique', 'message' => 'You already have a spouse record.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'spouse_user_id' => 'Spouse User Account',
            'spouse_user_guid' => 'Spouse User Account',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'birth_date' => 'Date of Birth',
            'email' => 'Email',
            'phone' => 'Phone',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getSpouseUser()
    {
        return $this->hasOne(User::class, ['id' => 'spouse_user_id']);
    }

    public function getDisplayName()
    {
        if ($this->spouse_user_id && $this->spouseUser) {
            return $this->spouseUser->displayName;
        }
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getDisplayEmail()
    {
        if ($this->spouse_user_id && $this->spouseUser) {
            return $this->spouseUser->email;
        }
        return $this->email;
    }

    public function beforeValidate()
    {
        // Convert spouse_user_guid to spouse_user_id BEFORE validation runs
        if (!empty($this->spouse_user_guid) && $this->spouse_user_guid !== '[]') {
            // Handle JSON array format from UserPickerField
            $guid = $this->spouse_user_guid;
            if (is_string($guid) && strpos($guid, '[') === 0) {
                $guids = json_decode($guid, true);
                if (is_array($guids) && !empty($guids)) {
                    $guid = $guids[0];
                }
            }
            
            $spouseUser = User::findOne(['guid' => $guid]);
            if ($spouseUser) {
                $this->spouse_user_id = $spouseUser->id;
                // Clear manual fields when linking to account
                $this->first_name = null;
                $this->last_name = null;
                $this->birth_date = null;
                $this->email = null;
            }
        }
        return parent::beforeValidate();
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        if ($this->spouse_user_id) {
            $this->createBidirectionalLink();
        }
        
        if (isset($changedAttributes['spouse_user_id']) && $changedAttributes['spouse_user_id'] !== $this->spouse_user_id) {
            $this->cleanupOldBidirectionalLink($changedAttributes['spouse_user_id']);
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();
        
        if ($this->spouse_user_id) {
            $reverseSpouse = static::findOne([
                'user_id' => $this->spouse_user_id,
                'spouse_user_id' => $this->user_id
            ]);
            
            if ($reverseSpouse) {
                $reverseSpouse->delete();
            }
        }
    }

    protected function createBidirectionalLink()
    {
        $existingReverse = static::findOne([
            'user_id' => $this->spouse_user_id,
            'spouse_user_id' => $this->user_id
        ]);
        
        if (!$existingReverse) {
            $reverseSpouse = new static();
            $reverseSpouse->user_id = $this->spouse_user_id;
            $reverseSpouse->spouse_user_id = $this->user_id;
            $reverseSpouse->save(false);
        }
    }

    protected function cleanupOldBidirectionalLink($oldSpouseUserId)
    {
        if ($oldSpouseUserId) {
            $oldReverseSpouse = static::findOne([
                'user_id' => $oldSpouseUserId,
                'spouse_user_id' => $this->user_id
            ]);
            
            if ($oldReverseSpouse) {
                $oldReverseSpouse->delete();
            }
        }
    }

    public function afterFind()
    {
        parent::afterFind();
        
        if ($this->spouse_user_id && $this->spouseUser) {
            $this->spouse_user_guid = $this->spouseUser->guid;
        }
    }

    public function getBirthDateTime()
    {
        if (empty($this->getEffectiveBirthDate())) {
            return null;
        }
        return DateTime::createFromFormat('Y-m-d', $this->getEffectiveBirthDate());
    }

    public function getEffectiveBirthDate()
    {
        if ($this->spouse_user_id && $this->spouseUser && $this->spouseUser->profile) {
            $dob = $this->spouseUser->profile->birthday;
            if ($dob) {
                return $dob;
            }
        }
        return $this->birth_date;
    }
}
