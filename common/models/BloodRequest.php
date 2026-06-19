<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class BloodRequest extends ActiveRecord
{
    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_FULFILLED = 'fulfilled';
    const STATUS_CANCELLED = 'cancelled';

    const PRIORITY_LOW    = 'low';
    const PRIORITY_NORMAL = 'normal';
    const PRIORITY_HIGH   = 'high';
    const PRIORITY_URGENT = 'urgent';

    public static function tableName()
    {
        return '{{%blood_requests}}';
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
            [['hospital_id', 'blood_type', 'units_needed', 'reason', 'needed_by'], 'required'],
            [['blood_type'], 'in', 'range' => Donor::BLOOD_TYPES],
            [['units_needed'], 'integer', 'min' => 1],
            [['units_fulfilled'], 'integer', 'min' => 0],
            [['reason'], 'string'],
            [['needed_by'], 'date', 'format' => 'php:Y-m-d'],
            [['status'], 'in', 'range' => [
                self::STATUS_PENDING,
                self::STATUS_APPROVED,
                self::STATUS_FULFILLED,
                self::STATUS_CANCELLED,
            ]],
            [['priority'], 'in', 'range' => [
                self::PRIORITY_LOW,
                self::PRIORITY_NORMAL,
                self::PRIORITY_HIGH,
                self::PRIORITY_URGENT,
            ]],
            [['hospital_id'], 'exist', 'targetClass' => Hospital::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'              => 'ID',
            'hospital_id'     => 'Hospitali',
            'blood_type'      => 'Blood type',
            'units_needed'    => 'Units needed',
            'units_fulfilled' => 'Units fulfilled',
            'priority'        => 'Priority',
            'status'          => 'Status',
            'reason'          => 'Reason',
            'needed_by'       => 'Needed by',
            'created_at'      => 'Created at',
            'updated_at'      => 'Updated at',
        ];
    }

    // Relationships
    public function getHospital()
    {
        return $this->hasOne(Hospital::class, ['id' => 'hospital_id']);
    }

    // Helper Methods
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isFulfilled()
    {
        return $this->status === self::STATUS_FULFILLED;
    }

    public function isUrgent()
    {
        return $this->priority === self::PRIORITY_URGENT;
    }

    public function getRemainingUnits()
    {
        // Hesabu units zinazobaki kufulfill
        return $this->units_needed - $this->units_fulfilled;
    }

    public function getProgressPercentage()
    {
        // Asilimia ya maombi yaliyofulfillwa
        if ($this->units_needed == 0) {
            return 0;
        }
        return round(($this->units_fulfilled / $this->units_needed) * 100);
    }

    public static function getUrgentRequests()
    {
        return static::find()
            ->where([
                'priority' => self::PRIORITY_URGENT,
                'status'   => self::STATUS_PENDING,
            ])
            ->orderBy(['needed_by' => SORT_ASC])
            ->all();
    }

    public static function getPendingRequestsByBloodType($bloodType)
    {
        return static::find()
            ->where([
                'blood_type' => $bloodType,
                'status'     => self::STATUS_PENDING,
            ])
            ->all();
    }
}