<?php

use yii\db\Migration;

class m260526_152041_create_users_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%users}}', [
            'id'         => $this->primaryKey(),
            'username'   => $this->string(50)->notNull()->unique(),
            'email'      => $this->string(100)->notNull()->unique(),
            'password'   => $this->string(255)->notNull(),
            'role' => $this->string(20)->notNull()->defaultValue('donor'),
            'status'     => $this->smallInteger()->notNull()->defaultValue(1),
            'auth_key'   => $this->string(32)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}