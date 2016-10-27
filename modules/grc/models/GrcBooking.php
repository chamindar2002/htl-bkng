<?php

namespace app\modules\grc\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\BlameableBehavior;
/**
 * This is the model class for table "grc_booking".
 *
 * @property integer $id
 * @property integer $reservation_id
 * @property integer $package_id
 * @property integer $agent_id
 * @property double $no_of_adults
 * @property integer $no_of_children
 * @property string $status
 * @property integer $created_by
 * @property string $created_at
 * @property string $updated_at
 *
 * @property GrcAgents $agent
 * @property GrcPackage $package
 * @property Reservations $reservation
 */
class GrcBooking extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'grc_booking';
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
            [['reservation_id', 'package_id', 'agent_id', 'no_of_adults', 'no_of_children'], 'required'],
            [['reservation_id', 'package_id', 'agent_id', 'no_of_children', 'created_by'], 'integer'],
            [['no_of_adults'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 10],
            [['agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => GrcAgents::className(), 'targetAttribute' => ['agent_id' => 'id']],
            [['package_id'], 'exist', 'skipOnError' => true, 'targetClass' => GrcPackage::className(), 'targetAttribute' => ['package_id' => 'id']],
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
            'reservation_id' => 'Reservation',
            'package_id' => 'Package',
            'agent_id' => 'Agent',
            'no_of_adults' => 'No Of Adults',
            'no_of_children' => 'No Of Children',
            'status' => 'Status',
            'created_by' => 'Created By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent()
    {
        return $this->hasOne(GrcAgents::className(), ['id' => 'agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPackage()
    {
        return $this->hasOne(GrcPackage::className(), ['id' => 'package_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReservation()
    {
        return $this->hasOne(Reservations::className(), ['id' => 'reservation_id']);
    }
}
