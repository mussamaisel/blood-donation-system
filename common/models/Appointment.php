<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Appointment extends ActiveRecord
{
    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public static function tableName()
    {
        return '{{%appointments}}';
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
            [['donor_id', 'hospital_id', 'appointment_date', 'appointment_time'], 'required'],
            [['appointment_date'], 'date', 'format' => 'php:Y-m-d'],
            [['appointment_time'], 'date', 'format' => 'php:H:i'],
            [['notes'], 'string'],
            [['status'], 'in', 'range' => [
                self::STATUS_PENDING,
                self::STATUS_APPROVED,
                self::STATUS_COMPLETED,
                self::STATUS_CANCELLED,
            ]],
            [['donor_id'], 'exist', 'targetClass' => Donor::class, 'targetAttribute' => 'id'],
            [['hospital_id'], 'exist', 'targetClass' => Hospital::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'               => 'ID',
            'donor_id'         => 'Donor',
            'hospital_id'      => 'Hospital',
            'appointment_date' => 'Appointment Date',
            'appointment_time' => 'Appointment Time',
            'status'           => 'Status',
            'notes'            => 'Notes',
            'created_at'       => 'Created at',
            'updated_at'       => 'Updated at',
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
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isUpcoming()
    {
        // Angalia kama miadi bado haijafika
        return strtotime($this->appointment_date) >= strtotime(date('Y-m-d'))
            && $this->status === self::STATUS_APPROVED;
    }

    public static function getUpcomingAppointments($donorId)
    {
        // Pata miadi inayokuja ya donor fulani
        return static::find()
            ->where([
                'donor_id' => $donorId,
                'status'   => self::STATUS_APPROVED,
            ])
            ->andWhere(['>=', 'appointment_date', date('Y-m-d')])
            ->orderBy(['appointment_date' => SORT_ASC])
            ->all();
    }

    public static function getTodayAppointments($hospitalId)
    {
        // Pata miadi ya leo ya hospitali fulani
        return static::find()
            ->where([
                'hospital_id'      => $hospitalId,
                'appointment_date' => date('Y-m-d'),
                'status'           => self::STATUS_APPROVED,
            ])
            ->orderBy(['appointment_time' => SORT_ASC])
            ->all();
    }
}