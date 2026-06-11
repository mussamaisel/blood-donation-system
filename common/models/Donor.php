<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Donor extends ActiveRecord
{
    const BLOOD_TYPES = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

    const GENDER_MALE   = 'male';
    const GENDER_FEMALE = 'female';

    const AVAILABLE     = 1;
    const NOT_AVAILABLE = 0;

    public static function tableName()
    {
        return '{{%donors}}';
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
            [['user_id', 'full_name', 'blood_type', 'gender', 'date_of_birth', 'phone', 'address', 'city', 'weight'], 'required'],
            [['full_name'], 'string', 'max' => 100],
            [['phone'], 'string', 'max' => 15],
            [['city'], 'string', 'max' => 50],
            [['address'], 'string'],
            [['weight'], 'number', 'min' => 50],
            [['blood_type'], 'in', 'range' => self::BLOOD_TYPES],
            [['gender'], 'in', 'range' => [self::GENDER_MALE, self::GENDER_FEMALE]],
            [['is_available'], 'integer'],
            [['date_of_birth', 'last_donation'], 'date', 'format' => 'php:Y-m-d'],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'user_id'       => 'User',
            'full_name'     => 'Full Name',
            'blood_type'    => 'Blood Type',
            'gender'        => 'Gender',
            'date_of_birth' => 'Date of Birth',
            'phone'         => 'Phone Number',
            'address'       => 'Address',
            'city'          => 'City',
            'weight'        => 'Weight (kg)',
            'is_available'  => 'Availability',
            'last_donation' => 'Last Donation',
            'created_at'    => 'Created at',
            'updated_at'    => 'Updated at',
        ];
    }

    // Relationships
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getDonations()
    {
        return $this->hasMany(Donation::class, ['donor_id' => 'id']);
    }

    public function getAppointments()
    {
        return $this->hasMany(Appointment::class, ['donor_id' => 'id']);
    }

    // Helper Methods
    public function isAvailable()
    {
        return $this->is_available === self::AVAILABLE;
    }

    public function getTotalDonations()
    {
        return $this->getDonations()->count();
    }

    public function canDonate()
    {
        // Donor anaweza kutoa damu kila baada ya miezi 3
        if ($this->last_donation === null) {
            return true;
        }
        $lastDonation = strtotime($this->last_donation);
        $threeMonthsAgo = strtotime('-3 months');
        return $lastDonation <= $threeMonthsAgo;
    }
}