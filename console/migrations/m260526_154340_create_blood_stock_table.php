<?php

use yii\db\Migration;

class m260526_154340_create_blood_stock_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%blood_stock}}', [
            'id'          => $this->primaryKey(),
            'hospital_id' => $this->integer()->notNull(),
            'blood_type'  => $this->string(5)->notNull(),
            'units'       => $this->integer()->notNull()->defaultValue(0),
            'expiry_date' => $this->date()->notNull(),
            'status'      => $this->string(20)->notNull()->defaultValue('available'),
            'created_at'  => $this->integer()->notNull(),
            'updated_at'  => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_blood_stock_hospital_id',
            '{{%blood_stock}}',
            'hospital_id',
            '{{%hospitals}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_blood_stock_hospital_id', '{{%blood_stock}}');
        $this->dropTable('{{%blood_stock}}');
    }
}