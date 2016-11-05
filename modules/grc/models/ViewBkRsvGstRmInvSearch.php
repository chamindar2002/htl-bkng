<?php

namespace app\modules\grc\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\grc\models\ViewBookingResvGuestRoomInvoice;

/**
 * This is the model class for table "view_booking_resv_guest_room_invoice".
 *
 * @property integer $booking_id
 * @property string $booking_status
 * @property double $no_of_adults
 * @property integer $no_of_children
 * @property integer $reservation_id
 * @property string $checkin_date
 * @property string $checkout_date
 * @property string $reservation_status
 * @property string $room_name
 * @property string $room_status
 * @property integer $guest_id
 * @property string $title
 * @property string $first_name
 * @property string $last_name
 * @property string $full_name
 * @property integer $agent_id
 * @property string $agent_name
 * @property integer $invoice_id
 * @property string $invoice_date
 * @property string $invoice_status
 */
class ViewBkRsvGstRmInvSearch extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'view_booking_resv_guest_room_invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['booking_id', 'no_of_children', 'reservation_id', 'guest_id', 'agent_id', 'invoice_id'], 'integer'],
            [['no_of_adults', 'checkin_date', 'checkout_date', 'room_name', 'room_status', 'title', 'first_name', 'last_name', 'agent_name'], 'required'],
            [['no_of_adults'], 'number'],
            [['checkin_date', 'checkout_date', 'invoice_date'], 'safe'],
            [['booking_status', 'invoice_status'], 'string', 'max' => 10],
            [['reservation_status', 'room_status', 'title'], 'string', 'max' => 20],
            [['room_name', 'first_name', 'last_name', 'agent_name'], 'string', 'max' => 64],
            [['full_name'], 'string', 'max' => 151],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'booking_id' => 'Booking ID',
            'booking_status' => 'Booking Status',
            'no_of_adults' => 'No Of Adults',
            'no_of_children' => 'No Of Children',
            'reservation_id' => 'Reservation ID',
            'checkin_date' => 'Checkin Date',
            'checkout_date' => 'Checkout Date',
            'reservation_status' => 'Reservation Status',
            'room_name' => 'Room Name',
            'room_status' => 'Room Status',
            'guest_id' => 'Guest ID',
            'title' => 'Title',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'full_name' => 'Full Name',
            'agent_id' => 'Agent ID',
            'agent_name' => 'Agent Name',
            'invoice_id' => 'Invoice ID',
            'invoice_date' => 'Invoice Date',
            'invoice_status' => 'Invoice Status',
        ];
    }
    
    public function search($params)
    {
        $query = ViewBookingResvGuestRoomInvoice::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $query->orderBy('booking_id desc');

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'booking_id' => $this->booking_id,
            'booking_status' => $this->booking_status,
            'no_of_adults' => $this->no_of_adults,
            'no_of_children' => $this->no_of_children,
            'reservation_id' => $this->reservation_id,
            'checkin_date' => $this->checkin_date,
            'checkout_date' => $this->checkout_date,
            'reservation_status' => $this->reservation_status,
            'room_name' => $this->room_name,
            'room_status' => $this->room_status,
            'guest_id' => $this->guest_id,
            'title' => $this->title,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'agent_id' => $this->agent_id,
            'agent_name' => $this->agent_name,
            'invoice_id' => $this->invoice_id,
            'invoice_date' => $this->invoice_date,
            'invoice_status' => $this->invoice_status,
        ]);

        $query->andFilterWhere(['like', 'status', $this->status]);
        return $dataProvider;
    }
}
