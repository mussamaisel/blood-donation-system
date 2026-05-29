<?php

use yii\db\Migration;

class m260526_154424_create_donations_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%donations}}', [
            'id'          => $this->primaryKey(),
            'donor_id'    => $this->integer()->notNull(),
            'hospital_id' => $this->integer()->notNull(),
            'blood_type'  => $this->string(5)->notNull(),
            'units'       => $this->integer()->notNull()->defaultValue(1),
            'status'      => $this->string(20)->notNull()->defaultValue('completed'),
            'notes'       => $this->text()->null(),
            'donated_at'  => $this->date()->notNull(),
            'created_at'  => $this->integer()->notNull(),
            'updated_at'  => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_donations_donor_id',
            '{{%donations}}',
            'donor_id',
            '{{%donors}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_donations_hospital_id',
            '{{%donations}}',
            'hospital_id',
            '{{%hospitals}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_donations_donor_id', '{{%donations}}');
        $this->dropForeignKey('fk_donations_hospital_id', '{{%donations}}');
        $this->dropTable('{{%donations}}');
    }
}