<?php

use yii\db\Migration;

class m161029_061202_create_inventory_tables_1 extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m161029_061202_create_inventory_tables_1 cannot be reverted.\n";
//
//        return true;
//    }

    
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        
        $this->createTable('invn_supplier', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'address' => $this->string(255),
            'phone' => $this->string(64),
            'email' => $this->string(64),
            'active'=>  $this->smallInteger()->defaultValue('1'),
            'deleted' => $this->smallInteger()->defaultValue('0'),
            'created_by' => $this->smallInteger()->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
        
        $this->insert('invn_supplier',array(
                                    'name'=>'No-Supplier',
                                    'active'=>'0',    
                                    'created_by'=>'1',
                                    'created_at'=>date('Y-m-d')
                                    ));
        $this->insert('invn_supplier',array(
                                    'name'=>'Thomson And Co. Ltd',
                                    'created_by'=>'1',
                                    'created_at'=>date('Y-m-d')
                                    ));
        
        $this->createTable('invn_item_master', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'sku' => $this->string(20),
            'parent_id'=>$this->integer()->defaultValue('0'),
            'supplier_id'=>$this->integer()->notNull(),
            'active'=>  $this->smallInteger()->defaultValue('1'),
            'deleted' => $this->smallInteger()->defaultValue('0'),
            'created_by' => $this->smallInteger()->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
        
        $this->insert('invn_item_master',array(
                                    'name'=>'Room Charges',
                                    'supplier_id'=>'1',
                                    'active'=>'0',    
                                    'created_by'=>'1',
                                    'created_at'=>date('Y-m-d')
                                    ));
        
        $this->createTable('invn_invoice', [
            'id' => $this->primaryKey(),
            'invoice_date' => $this->datetime()->notNull()->defaultValue(date('Y-m-d')),
            'reservation_id' => $this->integer()->notNull(),
            'booking_id'=>$this->integer()->defaultValue('0'),
            'status' => $this->char(10)->defaultValue('OPEN'),
            'active'=>  $this->smallInteger()->defaultValue('1'),
            'deleted' => $this->smallInteger()->defaultValue('0'),
            'created_by' => $this->smallInteger()->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
        
        $this->createTable('invn_invoice_items', [
            'id' => $this->primaryKey(),
            'invoice_id' => $this->integer()->notNull(),
            'item_master_id' => $this->integer()->notNull(),
            'item_description' => $this->string(64)->notNull(),
            'price'=>$this->double()->defaultValue('0'),
            'deleted' => $this->smallInteger()->defaultValue('0'),
            'created_by' => $this->smallInteger()->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
        
        $this->addForeignKey('FK_item_supplier', 'invn_item_master', 'supplier_id', 'invn_supplier', 'id');
        
        $this->addForeignKey('FK_invoice_reservation', 'invn_invoice', 'reservation_id', 'reservations', 'id');
        $this->addForeignKey('FK_invoice_booking', 'invn_invoice', 'booking_id', 'grc_booking', 'id');
        
        $this->addForeignKey('FK_invoice_invoice_items', 'invn_invoice_items', 'invoice_id', 'invn_invoice', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_item_supplier', 'invn_item_master');
        $this->dropForeignKey('FK_invoice_reservation', 'invn_invoice');
        $this->dropForeignKey('FK_invoice_booking', 'invn_invoice');
        $this->dropForeignKey('FK_invoice_invoice_items', 'invn_invoice_items');
        
        $this->dropTable('invn_supplier');
        $this->dropTable('invn_item_master');
        $this->dropTable('invn_invoice');
        $this->dropTable('invn_invoice_items');
    }
    
}
