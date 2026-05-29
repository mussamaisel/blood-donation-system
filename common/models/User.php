<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_INACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const ROLE_ADMIN = 'admin';
    const ROLE_DONOR = 'donor';
    const ROLE_HOSPITAL = 'hospital';
    // Virtual attributes
    public $password;
    public $confirm_password;

    public static function tableName()
    {
        return '{{%users}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['username'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['username', 'email'], 'unique'],
            [['role'], 'in', 'range' => [self::ROLE_ADMIN, self::ROLE_DONOR, self::ROLE_HOSPITAL]],
            [['status'], 'integer'],
            [['password'], 'string', 'min' => 6],
            [['confirm_password'], 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords do not match.'],
        ];
    }

    // IdentityInterface Methods
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    // Relationships
    public function getDonor()
    {
        return $this->hasOne(Donor::class, ['user_id' => 'id']);
    }

    public function getHospital()
    {
        return $this->hasOne(Hospital::class, ['user_id' => 'id']);
    }

    public function getNotifications()
    {
        return $this->hasMany(Notification::class, ['user_id' => 'id']);
    }

    // Helper Methods
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isDonor()
    {
        return $this->role === self::ROLE_DONOR;
    }

    public function isHospital()
    {
        return $this->role === self::ROLE_HOSPITAL;
    }
}