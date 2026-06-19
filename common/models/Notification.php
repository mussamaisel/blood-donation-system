<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Notification extends ActiveRecord
{
    const TYPE_INFO    = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_DANGER  = 'danger';

    const READ     = 1;
    const NOT_READ = 0;

    public static function tableName()
    {
        return '{{%notifications}}';
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
            [['user_id', 'title', 'message'], 'required'],
            [['title'], 'string', 'max' => 100],
            [['message'], 'string'],
            [['is_read'], 'integer'],
            [['type'], 'in', 'range' => [
                self::TYPE_INFO,
                self::TYPE_SUCCESS,
                self::TYPE_WARNING,
                self::TYPE_DANGER,
            ]],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => 'id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'user_id'    => 'User',
            'title'      => 'Tittle',
            'message'    => 'Message',
            'type'       => 'Type',
            'is_read'    => 'is read',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ];
    }

    // Relationships
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    // Helper Methods
    public function isRead()
    {
        return $this->is_read === self::READ;
    }

    public function markAsRead()
    {
        $this->is_read = self::READ;
        return $this->save();
    }

    public static function createNotification($userId, $title, $message, $type = self::TYPE_INFO)
    {
        // Tengeneza notification mpya kwa urahisi
        $notification          = new static();
        $notification->user_id = $userId;
        $notification->title   = $title;
        $notification->message = $message;
        $notification->type    = $type;
        return $notification->save();
    }

    public static function getUnreadCount($userId)
    {
        // Hesabu notifications ambazo bado hazijasomwa
        return static::find()
            ->where([
                'user_id' => $userId,
                'is_read' => self::NOT_READ,
            ])
            ->count();
    }

    public static function getUnreadNotifications($userId)
    {
        // Pata notifications zote ambazo bado hazijasomwa
        return static::find()
            ->where([
                'user_id' => $userId,
                'is_read' => self::NOT_READ,
            ])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
    }

    public static function markAllAsRead($userId)
    {
        // Weka notifications zote kuwa zimesomwa
        return static::updateAll(
            ['is_read' => self::READ],
            ['user_id' => $userId, 'is_read' => self::NOT_READ]
        );
    }
}