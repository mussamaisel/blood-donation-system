<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class BloodStock extends ActiveRecord
{
    const STATUS_AVAILABLE = 'available';
    const STATUS_EXPIRED   = 'expired';
    const STATUS_USED      = 'used';

    public static function tableName()
    {
        return '{{%blood_stock}}';
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
            [['hospital_id', 'blood_type', 'units', 'expiry_date'], 'required'],
            [['blood_type'], 'in', 'range' => Donor::BLOOD_TYPES],
            [['units'], 'integer', 'min' => 0],
            [['expiry_date'], 'date', 'format' => 'php:Y-m-d'],
            [['status'], 'in', 'range' => [
                self::STATUS_AVAILABLE,
                self::STATUS_EXPIRED,
                self::STATUS_USED
            ]],
            [['hospital_id'], 'exist', 'targetClass' => Hospital::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'hospital_id' => 'Hospitali',
            'blood_type'  => 'Aina ya Damu',
            'units'       => 'Idadi ya Units',
            'expiry_date' => 'Tarehe ya Kuisha',
            'status'      => 'Hali',
            'created_at'  => 'Imetengenezwa',
            'updated_at'  => 'Imebadilishwa',
        ];
    }

    // Relationships
    public function getHospital()
    {
        return $this->hasOne(Hospital::class, ['id' => 'hospital_id']);
    }

    // Helper Methods
    public function isAvailable()
    {
        return $this->status === self::STATUS_AVAILABLE && $this->units > 0;
    }

    public function isExpired()
    {
        return $this->status === self::STATUS_EXPIRED ||
               strtotime($this->expiry_date) < time();
    }

    public function addUnits($units)
    {
        $this->units += $units;
        return $this->save();
    }

    public function removeUnits($units)
    {
        // Angalia kama kuna units za kutosha
        if ($this->units < $units) {
            return false;
        }
        $this->units -= $units;
        return $this->save();
    }

    public static function getTotalByBloodType($bloodType)
    {
        return static::find()
            ->where(['blood_type' => $bloodType, 'status' => self::STATUS_AVAILABLE])
            ->sum('units');
    }
}