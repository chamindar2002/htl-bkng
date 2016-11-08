<?php

use yii\db\Migration;

class m161101_165859_create_view_booking extends Migration
{
    public function up()
    {
        $sql = "CREATE VIEW view_booking_resv_guest_room_invoice AS SELECT gbk.id AS booking_id, gbk.status AS booking_status, gbk.no_of_adults, gbk.no_of_children,
                rsv.id AS reservation_id, rsv.start AS checkin_date, rsv.end AS checkout_date,
                rsv.status AS reservation_status, rms.name AS room_name, rms.status AS room_status,
                gst.id AS guest_id, gst.title, gst.first_name, gst.last_name,
                CONCAT(gst.title,'. ',first_name ,' ',gst.last_name ) full_name,
                agt.id AS agent_id, agt.name AS agent_name,
                invn.id AS invoice_id, invn.invoice_date, invn.status AS invoice_status
                FROM grc_booking gbk
                JOIN reservations rsv ON rsv.id = gbk.reservation_id
                JOIN rooms rms ON rsv.room_id = rms.id
                JOIN grc_guests gst ON gbk.guest_id = gst.id
                JOIN grc_agents agt ON gbk.agent_id = agt.id
                LEFT JOIN invn_invoice invn ON gbk.id = invn.booking_id 
                ";
        $this->execute($sql);
    }

    public function down()
    {
        $this->execute('DROP VIEW view_booking_resv_guest_room_invoice');

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
