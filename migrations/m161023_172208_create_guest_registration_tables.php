<?php

use yii\db\Migration;

class m161023_172208_create_guest_registration_tables extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m161023_172208_create_guest_registration_tables cannot be reverted.\n";
//
//        return false;
//    }

   
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('grc_agents', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'agent_type' => $this->string(20)->notNull(),
            'active'=>$this->smallInteger()->defaultValue('1'),
            'deleted' => $this->smallInteger()->defaultValue('0'),
            'created_by' => $this->string(20)->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
        
        $this->insert('grc_agents',array(
                                    'name'=>'No-Agent',
                                    'agent_type' =>'DIRECT_BOOKING',
                                    'active'=>'0',
                                    'created_by'=>'1',
                                    'created_at'=>date('Y-m-d')
                                    )
                    );
        
        $this->createTable('grc_guests', [
            'id' => $this->primaryKey(),
            'title' => $this->string(20)->notNull(),
            'first_name' => $this->string(64)->notNull(),
            'last_name' => $this->string(64)->notNull(),
            'address' => $this->string(255),
            'post_code' => $this->string(64),
            'city' => $this->string(64),
            'country' => $this->string(64),
            'phone' => $this->string(64),
            'email' => $this->string(64),
            'nationality' => $this->string(64),
            'identification' => $this->string(64)->notNull(),
            'deleted' => $this->integer()->notNull(),
            'created_by' => $this->string(20)->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
        
        $this->createTable('grc_meal_plan', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'deleted' => $this->smallInteger()->defaultValue('0'),
            'created_by' => $this->string(20)->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
        
        $this->createTable('grc_package', [
            'id' => $this->primaryKey(),
            'room_id' => $this->integer()->notNull(),
            'meal_plan_id' => $this->integer()->notNull(),
            'price'=> $this->double()->notNull(),
            'active'=>$this->smallInteger()->defaultValue('1'),
            'created_by' => $this->string(20)->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
        
        $this->createTable('grc_booking', [
            'id' => $this->primaryKey(),
            'reservation_id' => $this->integer()->notNull(),
            'package_id' => $this->integer()->notNull(),
            'agent_id' => $this->integer()->notNull(),
            'no_of_adults'=> $this->double()->notNull(),
            'no_of_children' => $this->integer()->notNull(),
            'created_by' => $this->string(20)->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
        
        $this->addForeignKey('FK_package_room', 'grc_package', 'room_id', 'rooms', 'id');
        $this->addForeignKey('FK_package_meal_plan', 'grc_package', 'meal_plan_id', 'grc_meal_plan', 'id');
        
        $this->addForeignKey('FK_booking_reservation', 'grc_booking', 'reservation_id', 'reservations', 'id');
        $this->addForeignKey('FK_booking_package', 'grc_booking', 'package_id', 'grc_package', 'id');
        $this->addForeignKey('FK_booking_agent', 'grc_booking', 'agent_id', 'grc_agents', 'id');
        
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_package_room', 'grc_package');
        $this->dropForeignKey('FK_package_meal_plan', 'grc_package');
        
        $this->dropForeignKey('FK_booking_reservation', 'grc_booking');
        $this->dropForeignKey('FK_booking_package', 'grc_booking');
        $this->dropForeignKey('FK_booking_agent', 'grc_booking');
        
        $this->dropTable('grc_agents');
        $this->dropTable('grc_guests');
        $this->dropTable('grc_meal_plan');
        $this->dropTable('grc_package');
        $this->dropTable('grc_booking');
        
    }
}