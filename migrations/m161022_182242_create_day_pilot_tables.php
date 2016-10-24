<?php

use yii\db\Migration;


class m161022_182242_create_day_pilot_tables extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        //echo "m161022_182242_create_day_pilot_tables cannot be reverted.\n";
//
//        //return false;
//    }

    
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('rooms', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'capacity' => $this->integer(),
            'status' => $this->string(20)->notNull(),
            'deleted' => $this->smallInteger()->defaultValue('0'),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
        
        $this->createTable('reservations', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'start' =>  $this->dateTime()->notNull(),
            'end' =>  $this->dateTime()->notNull(),
            'room_id' => $this->integer()->notNull(),
            'status' => $this->string('20')->defaultValue('NEW'),
            'paid' => $this->integer(),
            'deleted'=> $this->smallInteger()->defaultValue('0'),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('rooms');
        $this->dropTable('reservations');
    }
    
}
