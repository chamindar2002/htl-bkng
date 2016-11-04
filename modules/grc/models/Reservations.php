<?php

namespace app\modules\grc\models;

use Yii;
use app\modules\grc\models\Reservations;

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
    
    public function datesConflict($request)
    {
        $response = array('result'=>'success', 'message'=>'Dates available', 'data'=>[]);
        
        $result = Yii::$app->db->createCommand('SELECT * FROM reservations WHERE NOT ((end <= :start) OR (start >= :end )) AND id <> :id AND room_id = :room')
                                    ->bindValue(':start', $request->post('checkin').' 12:00:00')
                                    ->bindValue(':end', $request->post('checkout').' 12:00:00')
                                    ->bindValue(':id', $request->post('reservation_id'))
                                    ->bindValue(':room', $request->post('room_id')) 
                                    ->queryAll();
        
        
        if(count($result) > 0){
            $response = array('result'=>'error', 'message'=>'Dates overlap with an existing reservation', 'data'=>$result);
        }
        
        return $response;
    }
}
