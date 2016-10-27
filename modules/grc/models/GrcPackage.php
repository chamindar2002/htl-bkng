<?php

namespace app\modules\grc\models;

use Yii;
use app\models\Rooms;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "grc_package".
 *
 * @property integer $id
 * @property integer $room_id
 * @property integer $meal_plan_id
 * @property double $price
 * @property integer $active
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property GrcBooking[] $grcBookings
 * @property GrcMealPlan $mealPlan
 * @property Rooms $room
 */
class GrcPackage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grc_package';
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
            [['room_id', 'meal_plan_id', 'price'], 'required'],
            [['room_id', 'meal_plan_id', 'active', 'created_by'], 'integer'],
            [['price'], 'number'],
            [['meal_plan_id'], 'exist', 'skipOnError' => true, 'targetClass' => GrcMealPlan::className(), 'targetAttribute' => ['meal_plan_id' => 'id']],
            [['room_id'], 'exist', 'skipOnError' => true, 'targetClass' => Rooms::className(), 'targetAttribute' => ['room_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'room_id' => 'Room',
            'meal_plan_id' => 'Meal Plan',
            'price' => 'Price',
            'active' => 'Active',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrcBookings()
    {
        return $this->hasMany(GrcBooking::className(), ['package_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMealPlan()
    {
        return $this->hasOne(GrcMealPlan::className(), ['id' => 'meal_plan_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoom()
    {
        return $this->hasOne(Rooms::className(), ['id' => 'room_id']);
    }
}
