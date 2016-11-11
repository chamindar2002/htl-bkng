<?php

namespace app\modules\inventory\models;

use Yii;

/**
 * This is the model class for table "invn_supplier".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property integer $active
 * @property integer $deleted
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property InvnItemMaster[] $invnItemMasters
 */
class InvnSupplier extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invn_supplier';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'created_by', 'created_at'], 'required'],
            [['active', 'deleted', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'phone', 'email'], 'string', 'max' => 64],
            [['address'], 'string', 'max' => 255],
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
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
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
    public function getInvnItemMasters()
    {
        return $this->hasMany(InvnItemMaster::className(), ['supplier_id' => 'id']);
    }
}
