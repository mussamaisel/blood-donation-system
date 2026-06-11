<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Hospital extends ActiveRecord
{
    const STATUS_VERIFIED   = 1;
    const STATUS_UNVERIFIED = 0;

    public static function tableName()
    {
        return '{{%hospitals}}';
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
            [['user_id', 'name', 'email', 'phone', 'address', 'city', 'region'], 'required'],
            [['name'], 'string', 'max' => 100],
            [['email'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['email'], 'unique'],
            [['phone'], 'string', 'max' => 15],
            [['city', 'region'], 'string', 'max' => 50],
            [['address'], 'string'],
            [['is_verified'], 'integer'],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'user_id'     => 'User',
            'name'        => 'Hospital Name',
            'email'       => 'Email',
            'phone'       => 'Phone Number',
            'address'     => 'Address',
            'city'        => 'City',
            'region'      => 'Region',
            'is_verified' => 'Verified',
            'created_at'  => 'Created at',
            'updated_at'  => 'Updated at',
        ];
    }

    // Relationships
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getBloodStocks()
    {
        return $this->hasMany(BloodStock::class, ['hospital_id' => 'id']);
    }

    public function getBloodRequests()
    {
        return $this->hasMany(BloodRequest::class, ['hospital_id' => 'id']);
    }

    public function getDonations()
    {
        return $this->hasMany(Donation::class, ['hospital_id' => 'id']);
    }

    public function getAppointments()
    {
        return $this->hasMany(Appointment::class, ['hospital_id' => 'id']);
    }

    // Helper Methods
    public function isVerified()
    {
        return $this->is_verified === self::STATUS_VERIFIED;
    }

    public function getBloodStockByType($bloodType)
    {
        return $this->getBloodStocks()
            ->where(['blood_type' => $bloodType])
            ->one();
    }

    public function getTotalStock()
    {
        return $this->getBloodStocks()->sum('units');
    }

    public function getPendingRequests()
    {
        return $this->getBloodRequests()
            ->where(['status' => 'pending'])
            ->count();
    }
}