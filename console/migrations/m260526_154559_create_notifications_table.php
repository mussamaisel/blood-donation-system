<?php

use yii\db\Migration;

class m260526_154559_create_notifications_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%notifications}}', [
            'id'         => $this->primaryKey(),
            'user_id'    => $this->integer()->notNull(),
            'title'      => $this->string(100)->notNull(),
            'message'    => $this->text()->notNull(),
            'type'       => $this->string(20)->notNull()->defaultValue('info'),
            'is_read'    => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_notifications_user_id',
            '{{%notifications}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_notifications_user_id', '{{%notifications}}');
        $this->dropTable('{{%notifications}}');
    }
}