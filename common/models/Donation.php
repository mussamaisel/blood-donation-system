<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Donation extends ActiveRecord
{
    const STATUS_COMPLETED = 'completed';
    const STATUS_PENDING   = 'pending';
    const STATUS_CANCELLED = 'cancelled';

    public static function tableName()
    {
        return '{{%donations}}';
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
            [['donor_id', 'hospital_id', 'blood_type', 'donated_at'], 'required'],
            [['blood_type'], 'in', 'range' => Donor::BLOOD_TYPES],
            [['units'], 'integer', 'min' => 1],
            [['notes'], 'string'],
            [['donated_at'], 'date', 'format' => 'php:Y-m-d'],
            [['status'], 'in', 'range' => [
                self::STATUS_COMPLETED,
                self::STATUS_PENDING,
                self::STATUS_CANCELLED,
            ]],
            [['donor_id'], 'exist', 'targetClass' => Donor::class, 'targetAttribute' => 'id'],
            [['hospital_id'], 'exist', 'targetClass' => Hospital::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'donor_id'    => 'Donor',
            'hospital_id' => 'Hospitali',
            'blood_type'  => 'Blood type',
            'units'       => 'Total units',
            'status'      => 'Status',
            'notes'       => 'Notes',
            'donated_at'  => 'Donated at',
            'created_at'  => 'Created at',
            'updated_at'  => 'Updated at',
        ];
    }

    // Relationships
    public function getDonor()
    {
        return $this->hasOne(Donor::class, ['id' => 'donor_id']);
    }

    public function getHospital()
    {
        return $this->hasOne(Hospital::class, ['id' => 'hospital_id']);
    }

    // Helper Methods
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public static function getTotalDonationsByBloodType($bloodType)
    {
        return static::find()
            ->where([
                'blood_type' => $bloodType,
                'status'     => self::STATUS_COMPLETED,
            ])
            ->sum('units');
    }

    public static function getRecentDonations($limit = 10)
    {
        return static::find()
            ->orderBy(['donated_at' => SORT_DESC])
            ->limit($limit)
            ->all();
    }
}