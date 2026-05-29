<?php

use yii\db\Migration;

class m260526_154458_create_blood_requests_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%blood_requests}}', [
            'id'            => $this->primaryKey(),
            'hospital_id'   => $this->integer()->notNull(),
            'blood_type'    => $this->string(5)->notNull(),
            'units_needed'  => $this->integer()->notNull(),
            'units_fulfilled' => $this->integer()->notNull()->defaultValue(0),
            'priority'      => $this->string(20)->notNull()->defaultValue('normal'),
            'status'        => $this->string(20)->notNull()->defaultValue('pending'),
            'reason'        => $this->text()->notNull(),
            'needed_by'     => $this->date()->notNull(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_blood_requests_hospital_id',
            '{{%blood_requests}}',
            'hospital_id',
            '{{%hospitals}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_blood_requests_hospital_id', '{{%blood_requests}}');
        $this->dropTable('{{%blood_requests}}');
    }
}