<?php

use yii\db\Migration;

class m260526_154015_create_donors_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%donors}}', [
            'id'            => $this->primaryKey(),
            'user_id'       => $this->integer()->notNull(),
            'full_name'     => $this->string(100)->notNull(),
            'blood_type'    => $this->string(5)->notNull(),
            'gender'        => $this->string(10)->notNull(),
            'date_of_birth' => $this->date()->notNull(),
            'phone'         => $this->string(15)->notNull(),
            'address'       => $this->text()->notNull(),
            'city'          => $this->string(50)->notNull(),
            'weight'        => $this->decimal(5,2)->notNull(),
            'is_available'  => $this->smallInteger()->notNull()->defaultValue(1),
            'last_donation' => $this->date()->null(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

        // Unganisha na users table
        $this->addForeignKey(
            'fk_donors_user_id',
            '{{%donors}}',
            'user_id',
            '{{%users}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_donors_user_id', '{{%donors}}');
        $this->dropTable('{{%donors}}');
    }
}