<?php

use yii\db\Migration;

class m161117_152309_create_payament_receipt_tables extends Migration
{
    /*public function up()
    {

    }

    public function down()
    {
        echo "m161117_152309_create_payament_receipt_tables cannot be reverted.\n";

        return false;
    }*/


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->createTable('payment_receipt_master', [
            'id' => $this->primaryKey(),
            'booking_id' => $this->integer()->notNull(),
            'receipt_date' => $this->datetime()->notNull(),
            'amount_paid' => $this->double()->notNull(),
            'pay_methods' => $this->text(),
            'is_cancelled'=>$this->smallInteger()->defaultValue(0),
            'deleted' => $this->smallInteger()->defaultValue('0'),
            'cancelled_by' => $this->smallInteger()->notNull(),
            'created_by' => $this->smallInteger()->notNull(),
            'created_at' => $this->datetime()->notNull(),
            'updated_at' => $this->datetime(),
        ]);

        $this->createTable('payment_receipt_invoice_items', [
            'receipt_id' => $this->integer()->notNull(),
            'invoice_item_id' => $this->integer()->notNull(),
        ]);



        $this->addForeignKey('FK_receipt_booking', 'payment_receipt_master', 'booking_id', 'grc_booking', 'id');
        $this->addForeignKey('FK_receipt_master_receipt_items', 'payment_receipt_invoice_items', 'receipt_id', 'payment_receipt_master', 'id');
        $this->addForeignKey('FK_receipt_master_invoice_items', 'payment_receipt_invoice_items', 'invoice_item_id', 'invn_invoice_items', 'id');
    }

    public function safeDown()
    {
        $this->dropForeignKey('FK_receipt_booking', 'payment_receipt_master');
        $this->dropForeignKey('FK_receipt_master_receipt_items', 'payment_receipt_invoice_items');
        $this->dropForeignKey('FK_receipt_master_invoice_items', 'payment_receipt_invoice_items');

        $this->dropTable('payment_receipt_master');
        $this->dropTable('payment_receipt_invoice_items');
    }

}
