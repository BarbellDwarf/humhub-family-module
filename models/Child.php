<?php

namespace humhub\modules\family\models;

use DateTime;
use Yii;
use humhub\modules\user\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Child Model
 *
 * Represents a child profile linked to a parent user.
 * Children can optionally link to an existing user account while appearing
 * in the parent's profile with birthdays integrated into the calendar system.
 *
 * @property int $id
 * @property int $user_id Parent user ID
 * @property string|null $first_name Child's first name
 * @property string|null $last_name Child's last name
 * @property string|null $birth_date Date of birth (Y-m-d format)
 * @property string $relation_type Relationship type for the profile user
 * @property int|null $child_user_id Optional linked child user ID
 * @property int $created_at Creation timestamp
 * @property int $updated_at Update timestamp
 *
 * @property User $user Parent user relation
 * @property User|null $childUser Linked child user relation
 */
class Child extends ActiveRecord
{
    public const RELATION_TYPE_CHILD = 'child';
    public const RELATION_TYPE_GRANDCHILD = 'grandchild';
    public const RELATION_TYPE_STEPCHILD = 'stepchild';
    public const RELATION_TYPE_FOSTER_CHILD = 'foster_child';

    /**
     * @var string|null Guid of selected child user account (for picker)
     */
    public $child_user_guid;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'child';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 100],
            ['birth_date', 'date', 'format' => 'php:Y-m-d'],
            ['birth_date', 'validateBirthDate'],
            [['child_user_guid'], 'safe'],
            [
                ['user_id'],
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
            [['first_name', 'last_name', 'birth_date'], 'required',
                'when' => function ($model) {
                    return !$model->hasLinkedUser();
                },
                'whenClient' => "function (attribute, value) {
                    var childGuid = $('#child-child_user_guid').val();
                    return !childGuid || childGuid === '' || childGuid === '[]';
                }",
                'message' => Yii::t('FamilyModule.base', 'Either select a user account or enter child details.')
            ],
        ];

        if ($this->supportsRelationType()) {
            $rules[] = ['relation_type', 'string', 'max' => 32];
            $rules[] = ['relation_type', 'default', 'value' => self::RELATION_TYPE_CHILD];
            $rules[] = ['relation_type', 'in', 'range' => array_keys(self::getRelationTypeOptions()), 'skipOnEmpty' => false];
        }

        if ($this->supportsChildUserAccount()) {
            $rules[] = [['child_user_id'], 'integer'];
            $rules[] = [
                ['child_user_id'],
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => ['child_user_id' => 'id'],
                'skipOnEmpty' => true,
                'skipOnError' => true
            ];
        }

        return $rules;
    }

    /**
     * Populate picker guids after fetching from DB.
     */
    public function afterFind()
    {
        parent::afterFind();

        if ($this->supportsChildUserAccount()) {
            $this->child_user_guid = $this->childUser ? $this->childUser->guid : null;
        }
    }

    /**
     * Convert picker guids to IDs before validation/save.
     *
     * @return bool
     */
    public function beforeValidate()
    {
        if ($this->supportsChildUserAccount()) {
            $this->child_user_id = $this->resolveGuidToId($this->child_user_guid);

            if ($this->child_user_id) {
                $this->first_name = null;
                $this->last_name = null;
                $this->birth_date = null;
            }
        }

        return parent::beforeValidate();
    }

    /**
     * Resolve a user guid to an ID.
     *
     * @param string|null $guid
     * @return int|null
     */
    protected function resolveGuidToId($guid)
    {
        if (is_array($guid)) {
            $guid = reset($guid);
        }

        if (is_string($guid) && strpos($guid, '[') === 0) {
            $guids = json_decode($guid, true);
            if (is_array($guids) && !empty($guids)) {
                $guid = $guids[0];
            }
        }

        if ($guid === '[]') {
            return null;
        }

        if (empty($guid)) {
            return null;
        }

        $user = User::findOne(['guid' => $guid]);
        return $user ? $user->id : null;
    }

    /**
     * Ensure birth date is not in the future.
     *
     * @param string $attribute
     */
    public function validateBirthDate($attribute)
    {
        if (empty($this->$attribute)) {
            return;
        }

        $date = DateTime::createFromFormat('Y-m-d', $this->$attribute);
        if (!$date) {
            $this->addError($attribute, Yii::t('FamilyModule.base', 'Birth date must be a valid date.'));
            return;
        }

        $today = new DateTime('today');
        if ($date > $today) {
            $this->addError($attribute, Yii::t('FamilyModule.base', 'Birth date cannot be in the future.'));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('FamilyModule.base', 'ID'),
            'user_id' => Yii::t('FamilyModule.base', 'Parent'),
            'first_name' => Yii::t('FamilyModule.base', 'First Name'),
            'last_name' => Yii::t('FamilyModule.base', 'Last Name'),
            'birth_date' => Yii::t('FamilyModule.base', 'Birth Date'),
            'relation_type' => Yii::t('FamilyModule.base', 'Relation'),
            'child_user_id' => Yii::t('FamilyModule.base', 'Child User Account'),
            'child_user_guid' => Yii::t('FamilyModule.base', 'Child User Account'),
            'created_at' => Yii::t('FamilyModule.base', 'Created At'),
            'updated_at' => Yii::t('FamilyModule.base', 'Updated At'),
        ];
    }

    public static function getRelationTypeOptions(): array
    {
        return [
            self::RELATION_TYPE_CHILD => Yii::t('FamilyModule.base', 'Child'),
            self::RELATION_TYPE_GRANDCHILD => Yii::t('FamilyModule.base', 'Grandchild'),
            self::RELATION_TYPE_STEPCHILD => Yii::t('FamilyModule.base', 'Stepchild'),
            self::RELATION_TYPE_FOSTER_CHILD => Yii::t('FamilyModule.base', 'Foster child'),
        ];
    }

    public static function getPrimaryRelationTypes(): array
    {
        return [
            self::RELATION_TYPE_CHILD,
            self::RELATION_TYPE_STEPCHILD,
            self::RELATION_TYPE_FOSTER_CHILD,
        ];
    }

    public function getRelationTypeLabel(): string
    {
        if (!$this->supportsRelationType()) {
            return self::getRelationTypeOptions()[self::RELATION_TYPE_CHILD];
        }

        $options = self::getRelationTypeOptions();
        return $options[$this->relation_type] ?? $options[self::RELATION_TYPE_CHILD];
    }

    public function supportsRelationType(): bool
    {
        return $this->hasAttribute('relation_type');
    }

    /**
     * Parent user relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Linked child user relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getChildUser()
    {
        return $this->hasOne(User::class, ['id' => 'child_user_id']);
    }

    /**
     * Calculates the child's current age from birth_date.
     *
     * @return int|null
     */
    public function getAge()
    {
        $birthDateValue = $this->getEffectiveBirthDate();
        if (empty($birthDateValue)) {
            return null;
        }

        $birthDate = DateTime::createFromFormat('Y-m-d', $birthDateValue);
        if (!$birthDate) {
            return null;
        }

        $today = new DateTime('today');
        return $birthDate->diff($today)->y;
    }

    /**
     * Returns child's full display name.
     *
     * @return string
     */
    public function getDisplayName()
    {
        if ($this->hasLinkedChildUser()) {
            return $this->childUser->displayName;
        }
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Returns parent's display name or empty string.
     *
     * @return string
     */
    public function getParentDisplayName()
    {
        return $this->user ? $this->user->displayName : '';
    }

    public function getEffectiveBirthDate()
    {
        if ($this->hasLinkedChildUser() && $this->childUser->profile) {
            $dob = $this->childUser->profile->birthday;
            if ($dob) {
                return $dob;
            }
        }
        return $this->birth_date;
    }

    protected function hasLinkedUser()
    {
        if (!$this->supportsChildUserAccount()) {
            return false;
        }

        if (!empty($this->child_user_id)) {
            return true;
        }

        return !empty($this->child_user_guid) && $this->child_user_guid !== '[]';
    }

    public function hasLinkedChildUser()
    {
        if (!$this->supportsChildUserAccount()) {
            return false;
        }

        return !empty($this->child_user_id) && $this->childUser;
    }

    public function supportsChildUserAccount()
    {
        return $this->hasAttribute('child_user_id');
    }
}
