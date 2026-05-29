<?php

use yii\db\Migration;

class m260526_154536_create_appointments_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%appointments}}', [
            'id'             => $this->primaryKey(),
            'donor_id'       => $this->integer()->notNull(),
            'hospital_id'    => $this->integer()->notNull(),
            'appointment_date' => $this->date()->notNull(),
            'appointment_time' => $this->time()->notNull(),
            'status'         => $this->string(20)->notNull()->defaultValue('pending'),
            'notes'          => $this->text()->null(),
            'created_at'     => $this->integer()->notNull(),
            'updated_at'     => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_appointments_donor_id',
            '{{%appointments}}',
            'donor_id',
            '{{%donors}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_appointments_hospital_id',
            '{{%appointments}}',
            'hospital_id',
            '{{%hospitals}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_appointments_donor_id', '{{%appointments}}');
        $this->dropForeignKey('fk_appointments_hospital_id', '{{%appointments}}');
        $this->dropTable('{{%appointments}}');
    }
}