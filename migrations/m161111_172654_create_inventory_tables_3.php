<?php

use yii\db\Migration;

class m161111_172654_create_inventory_tables_3 extends Migration
{
    /*public function up()
    {

    }

    public function down()
    {
        echo "m161111_172654_create_inventory_tables_3 cannot be reverted.\n";

        return false;
    }*/

    
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('invn_units', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'code' => $this->char(10),
            'parent_id'=>$this->integer()->defaultValue(0),
            'child_parent_convertion'=>$this->double()->defaultValue(0),
            'active'=> $this->smallInteger()->defaultValue('1'),
            'deleted' => $this->smallInteger()->defaultValue('0'),
            'created_by' => $this->smallInteger()->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
       

        $this->insert('invn_units',array(
            'name'=>'None',
            'code'=>'',
            'parent_id'=>'0',
            'child_parent_convertion'=>'1',
            'active' => '0',
            'deleted' => '1',
            'created_by'=>'1',
            'created_at'=>date('Y-m-d')
        ));
        
        $this->addColumn('invn_item_master', 'unit_id', 'INTEGER AFTER supplier_id');
        $this->addColumn('invn_item_master', 'reoder_point', 'INTEGER AFTER unit_id');
        $this->addColumn('invn_item_master', 'opening_stock', 'INTEGER AFTER reoder_point');
        $this->addColumn('invn_item_master', 'price', 'double AFTER opening_stock');
        
        $this->addColumn('invn_invoice_items', 'order_quantity', 'double AFTER price');
        $this->addColumn('invn_invoice_items', 'sub_total', 'double AFTER order_quantity');
        
    }

    public function safeDown()
    {
        
        $this->dropTable('invn_units');
        
        $this->dropColumn('invn_item_master', 'unit_id');
        $this->dropColumn('invn_item_master', 'reoder_point');
        $this->dropColumn('invn_item_master', 'opening_stock');
        $this->dropColumn('invn_item_master', 'price');
        
        $this->dropColumn('invn_invoice_items', 'order_quantity');
        $this->dropColumn('invn_invoice_items', 'sub_total');
        
    }
    
}
