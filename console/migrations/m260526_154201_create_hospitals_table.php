<?php

use yii\db\Migration;

class m260526_154201_create_hospitals_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%hospitals}}', [
            'id'           => $this->primaryKey(),
            'user_id'      => $this->integer()->notNull(),
            'name'         => $this->string(100)->notNull(),
            'email'        => $this->string(100)->notNull()->unique(),
            'phone'        => $this->string(15)->notNull(),
            'address'      => $this->text()->notNull(),
            'city'         => $this->string(50)->notNull(),
            'region'       => $this->string(50)->notNull(),
            'is_verified'  => $this->smallInteger()->notNull()->defaultValue(0),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_hospitals_user_id',
            '{{%hospitals}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_hospitals_user_id', '{{%hospitals}}');
        $this->dropTable('{{%hospitals}}');
    }
}