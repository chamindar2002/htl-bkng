<?php

namespace app\modules\inventory\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\inventory\models\ViewCustomerOrders;


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
 * @property string $order_status
 */


class ViewCustomerOrdersSearch extends  ViewCustomerOrders{
  
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
            [['id', 'item_master_id', 'category_name', 'checkin_date', 'checkout_date'], 'required'],
            [['id', 'invoice_item_id', 'item_master_id'], 'integer'],
            [['date_applicable', 'checkin_date', 'checkout_date'], 'safe'],
            [['price', 'order_quantity', 'total'], 'number'],
            [['item_description', 'category_name', 'room_name'], 'string', 'max' => 64],
            [['invoice_status', 'booking_status', 'order_status', 'send_notification'], 'string', 'max' => 10],
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
            'invoice_item_id' => 'Invoice Item Id',
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
            'order_status' => 'Status',
        ];
    }
    
    public function search($params)
    {
        $query = ViewCustomerOrders::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->orderBy('invoice_item_id DESC');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            //return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            
            'id' => $this->id,
            'invoice_item_id' => $this->invoice_item_id,
            'date_applicable' => $this->date_applicable,
            //'item_description' => $this->item_description,
            'item_master_id' => $this->item_master_id,
            'price' => $this->price,
            'order_quantity' => $this->order_quantity,
            'total' => $this->total,
            'invoice_status' => $this->invoice_status,
            'booking_status' => $this->booking_status,
            //'full_name' => $this->full_name,
            'category_name' => $this->category_name,
            'checkin_date' => $this->checkin_date,
            'checkout_date' => $this->checkout_date,
            'order_status' => $this->order_status,
            //'room_name' => $this->room_name,
            
        ]);

        $query->andFilterWhere(['like', 'item_description', $this->item_description])
              ->andFilterWhere(['like', 'full_name', $this->full_name]) 
              ->andFilterWhere(['like', 'room_name', $this->room_name]);  
        
        //var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);exit;
        
        return $dataProvider;
    }
    
    
}

?>
