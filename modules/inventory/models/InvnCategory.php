<?php

namespace app\modules\inventory\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "invn_category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent_id
 * @property integer $stock_deductable
 * @property integer $department_id
 * @property string $send_notification
 * @property integer $active
 * @property integer $deleted
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property InvnItemMaster[] $invnItemMasters
 */
class InvnCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invn_category';
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
            [['name'], 'required'],
            [['parent_id', 'stock_deductable', 'department_id', 'active', 'deleted', 'created_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 64],
            [['send_notification'], 'string', 'max' => 10],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => InvnDepartment::className(), 'targetAttribute' => ['department_id' => 'id']],
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
            'parent_id' => 'Parent ID',
            'stock_deductable' => 'Stock Deductable',
            'department_id' => 'Department',
            'send_notification' => 'Send Notification',
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
        return $this->hasMany(InvnItemMaster::className(), ['category_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(InvnDepartment::className(), ['id' => 'department_id']);
    }
    
}
