<?php

use yii\db\Migration;

class m161107_145013_create_inventory_tables_2 extends Migration
{
    /*public function up()
    {

    }

    public function down()
    {
        echo "m161107_145013_create_inventory_tables_2 cannot be reverted.\n";

        return false;
    }*/


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('invn_category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'parent_id' => $this->integer()->defaultValue(1),
            'stock_deductable' => $this->smallInteger()->defaultValue(1),
            'department_id' => $this->integer()->defaultValue(1),
            'send_notification' => $this->char(10)->defaultValue('NONE'),
            'active'=>  $this->smallInteger()->defaultValue(1),
            'deleted' => $this->smallInteger()->defaultValue(0),
            'created_by' => $this->smallInteger()->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
        
        $this->createTable('invn_department', [
            'id' => $this->primaryKey(),
            'name' => $this->string(64)->notNull(),
            'active'=> $this->smallInteger()->defaultValue('1'),
            'deleted' => $this->smallInteger()->defaultValue('0'),
            'created_by' => $this->smallInteger()->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);
       

        $this->insert('invn_category',array(
            'name'=>'Root',
            'parent_id'=>'0',
            'stock_deductable'=>'0',
            'department_id'=>'1',
            'active' => '0',
            'deleted' => '1',
            'created_by'=>'1',
            'created_at'=>date('Y-m-d')
        ));
        
        $this->insert('invn_department',array(
            'name'=>'No-department',
            'active' => '0',
            'deleted' => '1',
            'created_by'=>'1',
            'created_at'=>date('Y-m-d')
        ));
        
        

        $this->addForeignKey('FK_item_category', 'invn_item_master', 'category_id', 'invn_category', 'id');
        $this->addForeignKey('FK_category_department', 'invn_category', 'department_id', 'invn_department', 'id');
        
    }



    public function safeDown()
    {
        $this->dropForeignKey('FK_item_category', 'invn_item_master');
        $this->dropForeignKey('FK_category_department', 'invn_category');

        $this->dropTable('invn_category');
        $this->dropTable('invn_department');
        
    }

}
