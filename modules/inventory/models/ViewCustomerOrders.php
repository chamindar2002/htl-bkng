<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "view_customer_orders".
 *
 * @property integer $id
 * @property integer $invoice_item_id
 * @property string $date_applicable
 * @property string $item_description
 * @property integer $item_master_id
 * @property double $price
 * @property double $order_quantity
 * @property double $total
 * @property string $invoice_status
 * @property string $booking_status
 * @property string $full_name
 * @property string $category_name
 * @property string $send_notification
 * @property string $checkin_date
 * @property string $checkout_date
 * @property string $room_name
 */
class ViewCustomerOrders extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'view_customer_orders';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'invoice_item_id', 'date_applicable', 'item_description', 'item_master_id', 'category_name', 'checkin_date', 'checkout_date', 'room_name'], 'required'],
            [['id', 'invoice_item_id', 'item_master_id'], 'integer'],
            [['date_applicable', 'checkin_date', 'checkout_date'], 'safe'],
            [['price', 'order_quantity', 'total'], 'number'],
            [['item_description', 'category_name', 'room_name'], 'string', 'max' => 64],
            [['invoice_status', 'booking_status', 'send_notification'], 'string', 'max' => 10],
            [['full_name'], 'string', 'max' => 151],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_item_id'=> 'Invoice Item Id',
            'date_applicable' => 'Date Applicable',
            'item_description' => 'Item Description',
            'item_master_id' => 'Item Master ID',
            'price' => 'Price',
            'order_quantity' => 'Order Quantity',
            'total' => 'Total',
            'invoice_status' => 'Invoice Status',
            'booking_status' => 'Booking Status',
            'full_name' => 'Full Name',
            'category_name' => 'Category Name',
            'send_notification' => 'Send Notification',
            'checkin_date' => 'Checkin Date',
            'checkout_date' => 'Checkout Date',
            'room_name' => 'Room Name',
        ];
    }
}
