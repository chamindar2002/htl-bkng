<?php

namespace app\modules\inventory\models;

use app\models\User;
use Yii;
use app\modules\grc\models\GrcBooking;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "payment_receipt_master".
 *
 * @property integer $id
 * @property integer $booking_id
 * @property string $receipt_date
 * @property double $amount_paid
 * @property string $pay_methods
 * @property integer $is_cancelled
 * @property integer $deleted
 * @property integer $cancelled_by
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PaymentReceiptInvoiceItems[] $paymentReceiptInvoiceItems
 * @property GrcBooking $booking
 */


class PaymentReceiptMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_receipt_master';
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
            [['booking_id', 'receipt_date', 'amount_paid'], 'required'],
            [['booking_id', 'is_cancelled', 'deleted', 'cancelled_by', 'created_by'], 'integer'],
            [['receipt_date', 'created_at', 'updated_at'], 'safe'],
            [['pay_methods'], 'string', 'max' => 255],
            [['amount_paid'], 'number'],
            [['booking_id'], 'exist', 'skipOnError' => true, 'targetClass' => GrcBooking::className(), 'targetAttribute' => ['booking_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'booking_id' => 'Booking ID',
            'receipt_date' => 'Receipt Date',
            'amount_paid' => 'Amount Paid',
            'pay_methods' => 'Paid By',
            'is_cancelled' => 'Is Cancelled',
            'deleted' => 'Deleted',
            'cancelled_by' => 'Cancelled By',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentReceiptInvoiceItems()
    {
        return $this->hasMany(PaymentReceiptInvoiceItems::className(), ['receipt_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(GrcBooking::className(), ['id' => 'booking_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id'=>'created_by']);
    }
}
