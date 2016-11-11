<?php

namespace app\modules\inventory\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "invn_item_master".
 *
 * @property integer $id
 * @property string $name
 * @property string $sku
 * @property integer $category_id
 * @property integer $supplier_id
 * @property integer $unit_id 
 * @property integer $reoder_point 
 * @property integer $opening_stock 
 * @property integer $active
 * @property integer $deleted
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property InvnCategory $category
 * @property InvnSupplier $supplier
 */
class InvnItemMaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invn_item_master';
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
            [['name', 'category_id', 'supplier_id'], 'required'],
            [['category_id', 'supplier_id', 'unit_id', 'reoder_point', 'opening_stock', 'active', 'deleted', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 64],
            [['sku'], 'string', 'max' => 20],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => InvnCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['supplier_id'], 'exist', 'skipOnError' => true, 'targetClass' => InvnSupplier::className(), 'targetAttribute' => ['supplier_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sku' => 'Sku',
            'category_id' => 'Category',
            'supplier_id' => 'Supplier',
            'unit_id' => 'Unit', 
            'reoder_point' => 'Reoder Point', 
            'opening_stock' => 'Opening Stock', 
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
    public function getCategory()
    {
        return $this->hasOne(InvnCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSupplier()
    {
        return $this->hasOne(InvnSupplier::className(), ['id' => 'supplier_id']);
    }
}
