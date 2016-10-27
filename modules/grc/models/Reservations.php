<?php

namespace app\modules\grc\models;

use Yii;

/**
 * This is the model class for table "reservations".
 *
 * @property integer $id
 * @property string $name
 * @property string $start
 * @property string $end
 * @property integer $room_id
 * @property string $status
 * @property integer $paid
 * @property integer $deleted
 * @property string $created_at
 * @property string $updated_at
 *
 * @property GrcBooking[] $grcBookings
 */
class Reservations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'reservations';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'start', 'end', 'room_id', 'created_at'], 'required'],
            [['start', 'end', 'created_at', 'updated_at'], 'safe'],
            [['room_id', 'paid', 'deleted'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['status'], 'string', 'max' => 20],
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
            'start' => 'Start',
            'end' => 'End',
            'room_id' => 'Room ID',
            'status' => 'Status',
            'paid' => 'Paid',
            'deleted' => 'Deleted',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGrcBookings()
    {
        return $this->hasMany(GrcBooking::className(), ['reservation_id' => 'id']);
    }
    
    public function getRoom()
    {
        return $this->hasOne(\app\models\Rooms::className(), ['id' => 'room_id']);
    }
}
