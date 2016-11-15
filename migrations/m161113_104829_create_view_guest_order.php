<?php

use yii\db\Migration;
use yii\db\Schema;

class m161113_104829_create_view_guest_order extends Migration
{
    public function up()
    {
        $this->addColumn('invn_invoice_items','status',Schema::TYPE_CHAR."(10) DEFAULT 'OPEN'");
        
        $sql = "CREATE VIEW view_customer_orders AS SELECT invitm.invoice_id AS id, invitm.id AS invoice_item_id, invitm.date_applicable, invitm.item_description, invitm.item_master_id, 
                invitm.status AS order_status,invitm.price, invitm.order_quantity, 
                (invitm.order_quantity * invitm.price) AS total,  inv.status AS invoice_status,
                bkg.status AS booking_status, CONCAT(gst.title,'. ',gst.first_name ,' ',gst.last_name ) full_name,
                cat.name AS category_name, cat.send_notification,
                rsv.start AS checkin_date, rsv.end AS checkout_date, rms.name AS room_name,
				CONCAT(emp.first_name, ' ',emp.last_name) employee_name,
				emp.designation, dtb.title
                FROM invn_invoice_items invitm
                JOIN invn_invoice inv on (invitm.invoice_id = inv.id)
                JOIN grc_booking bkg on (inv.booking_id = bkg.id)
                JOIN grc_guests gst on (bkg.guest_id = gst.id)
                JOIN invn_item_master itm on (invitm.item_master_id = itm.id) 
                JOIN invn_category cat on (itm.category_id = cat.id)
                JOIN reservations rsv on (bkg.reservation_id = rsv.id)
                JOIN rooms rms on (rsv.room_id = rms.id)
				JOIN emp_employees emp on (inv.employee_id = emp.id)
				JOIN dinning_tables dtb on (inv.table_id = dtb.id)
                WHERE invitm.package_id = 1 AND invitm.deleted = 0;
                ";
        $this->execute($sql);
    }

    public function down()
    {
        $this->execute('DROP VIEW view_customer_orders');
        $this->dropColumn('invn_invoice_items', 'status');

        return true;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
