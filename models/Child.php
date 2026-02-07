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
 * Children do not have their own user accounts but appear in the parent's profile
 * and have their birthdays integrated into the calendar system.
 *
 * @property int $id
 * @property int $user_id Parent user ID
 * @property string $first_name Child's first name
 * @property string $last_name Child's last name
 * @property string $birth_date Date of birth (Y-m-d format)
 * @property int|null $mother_id Optional mother user ID
 * @property int|null $father_id Optional father user ID
 * @property int $created_at Creation timestamp
 * @property int $updated_at Update timestamp
 *
 * @property User $user Parent user relation
 * @property User|null $mother Mother user relation
 * @property User|null $father Father user relation
 */
class Child extends ActiveRecord
{
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
        return [
            [['user_id', 'first_name', 'last_name', 'birth_date'], 'required'],
            [['user_id', 'mother_id', 'father_id'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 100],
            ['birth_date', 'date', 'format' => 'php:Y-m-d'],
            ['birth_date', 'validateBirthDate'],
            [
                ['user_id'],
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']
            ],
            [
                ['mother_id'],
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => ['mother_id' => 'id'],
                'skipOnEmpty' => true,
                'skipOnError' => true
            ],
            [
                ['father_id'],
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => ['father_id' => 'id'],
                'skipOnEmpty' => true,
                'skipOnError' => true
            ],
        ];
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
            'mother_id' => Yii::t('FamilyModule.base', 'Mother'),
            'father_id' => Yii::t('FamilyModule.base', 'Father'),
            'created_at' => Yii::t('FamilyModule.base', 'Created At'),
            'updated_at' => Yii::t('FamilyModule.base', 'Updated At'),
        ];
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
     * Mother user relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMother()
    {
        return $this->hasOne(User::class, ['id' => 'mother_id']);
    }

    /**
     * Father user relation.
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFather()
    {
        return $this->hasOne(User::class, ['id' => 'father_id']);
    }

    /**
     * Calculates the child's current age from birth_date.
     *
     * @return int|null
     */
    public function getAge()
    {
        if (empty($this->birth_date)) {
            return null;
        }

        $birthDate = DateTime::createFromFormat('Y-m-d', $this->birth_date);
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
}
