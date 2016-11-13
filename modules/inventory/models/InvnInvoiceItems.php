<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "invn_invoice_items".
 *
 * @property integer $id
 * @property integer $invoice_id
 * @property integer $item_master_id
 * @property string $item_description
 * @property double $price
 * @property integer $deleted
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property InvnInvoice $invoice
 */
class InvnInvoiceItems extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invn_invoice_items';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['invoice_id', 'item_master_id', 'item_description', 'created_by', 'created_at'], 'required'],
            [['invoice_id', 'item_master_id', 'deleted', 'created_by'], 'integer'],
            [['price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['item_description'], 'string', 'max' => 64],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => InvnInvoice::className(), 'targetAttribute' => ['invoice_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'invoice_id' => 'Invoice ID',
            'item_master_id' => 'Item Master ID',
            'item_description' => 'Item Description',
            'price' => 'Price',
            'deleted' => 'Deleted',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(InvnInvoice::className(), ['id' => 'invoice_id']);
    }
    
    public static function deleteItem($id)
    {
       $item = self::find()->where(['id'=>$id])->one();   
       if($item->invoice->booking->status == 'OPEN'){
            $item->deleted = 1;
            return $item->update();
       }

    }
    
    
}
