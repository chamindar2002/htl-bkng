<?php

namespace app\modules\inventory\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
use app\modules\grc\models\GrcBooking;
use app\modules\grc\models\Reservations;
/**
 * This is the model class for table "invn_invoice".
 *
 * @property integer $id
 * @property string $invoice_date
 * @property integer $reservation_id
 * @property integer $booking_id
 * @property string $status
 * @property integer $active
 * @property integer $deleted
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property GrcBooking $booking
 * @property Reservations $reservation
 * @property InvnInvoiceItems[] $invnInvoiceItems
 */
class InvnInvoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invn_invoice';
    }
    
    public function behaviors()
    {
         return [
               [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => 'created_at',
                    'updatedAtAttribute' => 'updated_at',
                    'value' => new Expression('NOW()'),
               ],
               [
                   'class' => BlameableBehavior::className(),
                   'createdByAttribute' => 'created_by',
                   'updatedByAttribute' => false,
                   'value'=>Yii::$app->user->identity->id,
               ] 
               
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_date'], 'safe'],
            [['reservation_id'], 'required'],
            [['reservation_id', 'booking_id', 'active', 'deleted', 'created_by'], 'integer'],
            [['status'], 'string', 'max' => 10],
            [['booking_id'], 'exist', 'skipOnError' => true, 'targetClass' => GrcBooking::className(), 'targetAttribute' => ['booking_id' => 'id']],
            [['reservation_id'], 'exist', 'skipOnError' => true, 'targetClass' => Reservations::className(), 'targetAttribute' => ['reservation_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_date' => 'Invoice Date',
            'reservation_id' => 'Reservation ID',
            'booking_id' => 'Booking ID',
            'status' => 'Status',
            'active' => 'Active',
            'deleted' => 'Deleted',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(GrcBooking::className(), ['id' => 'booking_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReservation()
    {
        return $this->hasOne(Reservations::className(), ['id' => 'reservation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvnInvoiceItems()
    {
        return $this->hasMany(InvnInvoiceItems::className(), ['invoice_id' => 'id']);
    }
}
