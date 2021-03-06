<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\helpers\Json;
use CJSON;


class DashboardController extends ApiController {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [],
                'rules' => [
                    [
                        'actions' => ['index', 'rooms', 'events', 'newrsv',
                                      'editrsv', 'create-new-rsv', 'update-rsv',
                                      'new-room', 'move-rsv', 'destroy-rsv', 'create-new-room',
                                      'edit-room', 'update-room', 'resize-rsv'],
                        'allow' => true,
                        //'roles' => ['@'], 
                        'roles' => ['user-role'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'rooms' => ['POST'],
                    'newrsv'=>['GET', 'HEAD'],
                    'editrsv'=>['GET', 'HEAD'],
                    'create-new-rsv'=>['POST'],
                    'update-rsv'=>['POST'],
                    'move-rsv'=>['POST'],
                    'destroy-rsv'=>['POST'],
                    'new-room'=>['GET', 'HEAD'], 
                    'create-new-room'=>['POST'],
                    'edit-room'=>['GET', 'HEAD'],
                    'update-room'=>['POST'],
                    'resize-rsv'=>['POST']
                ],
            ],
        ];
    }

    public function actionIndex() {
        //return $this->render('index');
        return $this->render('agenda');
    }

    public function actionRooms() {

      $request = Yii::$app->request;
      
      if($request->isAjax){
          $query = new Query;
          $capacity = $request->post('capacity') ? $request->post('capacity') : 0; 
          $query->select('*')
                ->from('rooms');
          if($capacity > 0){   
                $query->where('capacity = '.$capacity);
          }
                
          $rooms = $query->all();
         
          $result = array();
          
          foreach($rooms as $room) {

                $result[] = array(
                    'id' => $room['id'],
                    'name' => $room['name'],
                    'capacity' => $room['capacity'],
                    'status' => $room['status']
                );

          }
          
          
          $this->renderJSON($result);
      }  
        
           
       
    }
    
    public function actionEvents()
    {
        $query = new Query;
        $query->select('id, name AS text, start, end, room_id AS resource, name AS bubbleHtml, status, paid')
                ->from('reservations')
                ->where('end <= '.date('Y-m-d', strtotime($_POST['start'])))
                ->orWhere('start >='.date('Y-m-d', strtotime($_POST['end'])))
                ->where('deleted=0')
                ->limit(100);
        $rows = $query->all();
        //return Json::encode($rows);
        $this->renderJSON($rows);
      
        
    }
    
    public function actionNewrsv()
    {
        $this->layout = 'dp_modal';
        $request = Yii::$app->request;
                
        $query = new Query;
        $query->select('*')
                ->from('rooms');
        $rooms = $query->all();
        
        $param = array(
            'start'=>$request->get('start'),
            'end'=>$request->get('end'),
            'room_id'=>$request->get('resource')
        );
        return $this->render('_newresv_form', ['param'=>$param, 'rooms'=>$rooms]);
    }
    
    public function actionCreateNewRsv()
    {
        
        $request = Yii::$app->request;
        $response = array();
        
        $data = array(
            'name'=>$request->post('name'),
            'start'=>$request->post('start'). Yii::$app->params['check_in_time'],
            'end'=>$request->post('end').Yii::$app->params['check_in_time'],
            'room_id'=>$request->post('room'),
            'status'=>'New',
            'paid'=>0
            );
        
        $res = Yii::$app->db->createCommand()->insert('reservations',$data)->execute();
        if($res){
            
           $response = array(
               'result'=>'OK',
               'message'=>'Created with id: '.Yii::$app->db->getLastInsertID(),
               'id' => Yii::$app->db->getLastInsertID(),
           );
            
        }
        
       $this->renderJSON($response);
        
    }
    
    public function actionEditrsv()
    {
        
        $this->layout = 'dp_modal';
        $request = Yii::$app->request;
        
        $query = new Query;
        $query->select('*')
                ->from('reservations')
                ->where('id ='.$request->get('id'));
        $data = $query->one();
        
        $query_1 = new Query;
        $query_1->select('*')->from('rooms');
        
        $rooms = $query_1->all();
        
        //$this->dd($data);
              
        return $this->render('_editresv_form', ['data'=>$data, 'rooms'=>$rooms]);
    }
    
    public function actionUpdateRsv()
    {

        $request = Yii::$app->request;
        $response = array();

        $this->renderJSON($this->reservationIsBooked($request->post('id')));

        $data = array(
            'name'=>$request->post('name'),
            'start'=>$request->post('start'). Yii::$app->params['check_in_time'],
            'end'=>$request->post('end'). Yii::$app->params['check_in_time'],
            'room_id'=>$request->post('room'),
            'status'=>$request->post('status'),
            'paid'=>$request->post('paid')
            );
        
        $res = Yii::$app->db->createCommand()->update('reservations',$data,'id=:id', array(':id'=>$request->post('id')))->execute();
        if($res){
            
            $response = array(
                'result'=>'OK',
                'message'=>'Update successful',
            );
            
        }
        
        $this->renderJSON($response);
    }
    
    public function actionMoveRsv(){
        
        $request = Yii::$app->request;
        $response = array('result'=>'Error', 'message'=>'Nothing to modify');

        $this->renderJSON($this->reservationIsBooked($request->post('id')));
        
        #SELECT * FROM reservations WHERE NOT ((end <= :start) OR (start >= :end)) AND id <> :id AND room_id = :resource
        $overlaps = Yii::$app->db->createCommand('SELECT * FROM reservations WHERE NOT ((end <= :start) OR (start >= :end )) AND id <> :id AND room_id = :resource')
                                    ->bindValue(':start', $request->post('newStart'))
                                    ->bindValue(':end', $request->post('newEnd'))
                                    ->bindValue(':id', $request->post('id'))
                                    ->bindValue(':resource', $request->post('newResource')) 
                                    ->queryAll();
        
        if ($overlaps) {      
            $this->renderJSON(array('result'=>'Error', 'message'=>'This reservation overlaps with an existing reservation.'));
            exit();
        }
        
        $data = array(
            'start'=>$request->post('newStart'),
            'end'=>$request->post('newEnd'),
            'room_id'=>$request->post('newResource'),
           );
        
        $res = Yii::$app->db->createCommand()->update('reservations',$data,'id=:id', array(':id'=>$request->post('id')))->execute();
        if($res){
            
            $response = array(
                'result'=>'OK',
                'message'=>'Update successful',
            );
            
            
        }
        
       $this->renderJSON($response);
    }
    
    public function actionDestroyRsv()
    {
        
        $request = Yii::$app->request;

        $this->renderJSON($this->reservationIsBooked($request->post('id')));

        $response = array();
        $data = array('deleted'=>1);
        
        $res = Yii::$app->db->createCommand()->update('reservations',$data,'id=:id', array(':id'=>$request->post('id')))->execute();
        
        if($res){
            
            $response = array(
                'result'=>'OK',
                'message'=>'Update successful',
            );
            
        }
        
        $this->renderJSON($response);
    }
    
    public function actionNewRoom()
    {
        
        $this->layout = 'dp_modal';
        $request = Yii::$app->request;
         
        return $this->render('_newroom_form');
    }
    
    public function actionCreateNewRoom()
    {
        
        $request = Yii::$app->request;
        $response = array();
        
        $data = array(
                    'name'=>$request->post('name'),
                    'capacity'=>$request->post('capacity'),
                    'status'=>'Ready'
                );
        
        $res = Yii::$app->db->createCommand()->insert('rooms',$data)->execute();
        if($res){
            
           $response = array(
               'result'=>'OK',
               'message'=>'Created with id: '.Yii::$app->db->getLastInsertID(),
               'id' => Yii::$app->db->getLastInsertID(),
           );
            
        }
        
       $this->renderJSON($response);
        
        
    }
    
    public function actionEditRoom()
    {
        
        $this->layout = 'dp_modal';
        $request = Yii::$app->request;
        
        $query = new Query;
        $query->select('*')
                ->from('rooms')
                ->where('id ='.$request->get('id'));
        $data = $query->one();
        
        return $this->render('_editroom_form', ['data'=>$data]);
    }
    
    public function actionUpdateRoom()
    {
        
       $request = Yii::$app->request;
       $response = array();
        
        $data = array(
                    'name'=>$request->post('name'),
                    'capacity'=>$request->post('capacity'),
                    'status'=>$request->post('status')
                );
        
        $res = Yii::$app->db->createCommand()->update('rooms',$data,'id=:id', array(':id'=>$request->post('id')))->execute();
        if($res){
            
            $response = array(
                'result'=>'OK',
                'message'=>'Update successful',
            );
            
        }
        
        $this->renderJSON($response); 
        
    }
    
    public function actionResizeRsv()
    {
        
        $request = Yii::$app->request;
        $response = array();

        $this->renderJSON($this->reservationIsBooked($request->post('id')));
        
        $data = array(
                    'start'=>$request->post('newStart'),
                    'end'=>$request->post('newEnd'),
                 );
        
        $res = Yii::$app->db->createCommand()->update('reservations',$data,'id=:id', array(':id'=>$request->post('id')))->execute();
        if($res){
            
            $response = array(
                'result'=>'OK',
                'message'=>'Update successful',
            );
            
        }
        
        $this->renderJSON($response);
    }
    
   

}
