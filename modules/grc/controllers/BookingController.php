<?php

namespace app\modules\grc\controllers;

use app\modules\inventory\models\InvnInvoice;
use Yii;
use app\modules\grc\models\GrcBooking;
use app\modules\grc\models\BookingSearch;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\components\GrcUtilities;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\db\Query;
use app\modules\inventory\models\InvnInvoiceItems;
use Pusher;
use app\components\PusherHelper;

/**
 * BookingController implements the CRUD actions for GrcBooking model.
 */
class BookingController extends \app\controllers\ApiController
{
    
    /**
     * @inheritdoc
     */

     public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 
                                      'delete','confirm', 'search-reservations',
                                      'fetch-guests', 'update-package-inv-item',
                                      'dashboard', 'check-resv-availability', 'update-reservation-dates',
                                      'place-order', 'test-pusher', 'fetch-order', 'cancel-order-item'],
                        'allow' => true,
                        //'roles' => ['@'], 
                        'roles' => ['user-role'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'search-reservations' => ['POST'],
                    
                ],
            ],
        ];
    }

    /**
     * Lists all GrcBooking models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GrcBooking model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $agents = ArrayHelper::map(\app\modules\grc\models\GrcAgents::find()->where(['active'=>1])->orWhere('id=1')->all(), 'id', 'name');
        $rooms = ArrayHelper::map(\app\models\Rooms::find()->where(['deleted'=>0])->all(), 'id', 'name');

        $invoice = \app\modules\inventory\models\InvnInvoice::find()->where(['booking_id'=>$id, 'deleted'=>0])->one();
        $available_packages = \app\modules\grc\models\GrcPackage::getAvailableRoomPackagesByRoom($model->reservation->room->attributes['id']);
                
        
        $data = array(
                'reservation_data'=>$model->reservation->attributes,
                'room_data'=>$model->reservation->room->attributes,
                'booking_data'=>$model->attributes,
                'guest_data'=>$model->guest->attributes,
                'date_allocation' => GrcUtilities::computeDatesAllocation($model->reservation->attributes['start'], $model->reservation->attributes['end']),
                'available_room_packages' => \app\modules\grc\models\GrcPackage::getAvailableRoomPackagesByRoom($model->reservation->room->attributes['id'])
            );
        
        return $this->render('view', [
            'model' => $model,'data'=>$data, 'invoice'=>$invoice
        ]);
    }

    /**
     * Creates a new GrcBooking model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GrcBooking();
       
        //$guests = ArrayHelper::map(\app\modules\grc\models\GrcGuest::find()->where(['deleted'=>0])->all(), 'id', 'last_name');
        $agents = ArrayHelper::map(\app\modules\grc\models\GrcAgents::find()->where(['active'=>1])->orWhere('id=1')->all(), 'id', 'name');
        $rooms = ArrayHelper::map(\app\models\Rooms::find()->where(['deleted'=>0])->all(), 'id', 'name');
        
        //\yii\helpers\VarDumper::dump($rooms);exit();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        
            $data = array(
                'reservation_data'=>$model->reservation->attributes,
                'room_data'=>$model->reservation->room->attributes,
                'booking_data'=>$model->attributes,
                'guest_data'=>$model->guest->attributes,
                'date_allocation' => GrcUtilities::computeDatesAllocation($model->reservation->attributes['start'], $model->reservation->attributes['end']),
                'available_room_packages' => \app\modules\grc\models\GrcPackage::getAvailableRoomPackagesByRoom($model->reservation->room->attributes['id'])
            );
            
            if(Yii::$app->request->isAjax){
                $this->renderJson(['result'=>'success', 'message'=>'Success', 'data'=>$data]);
                Yii::$app->end();
            }
            
             return $this->redirect(['update', 'id' => $model->id]);
            
        } else {
           
            //var_dump($model->getErrors());
            if(!empty($model->getErrors()) && Yii::$app->request->isAjax)
                $this->renderJson(['result'=>'fail', 'message'=>'Fail', 'data'=>$model->getErrors()]);
            
            return $this->render('create', [
                'model' => $model, 'agents'=>$agents, 'rooms'=>$rooms
            ]);
        }
    }

    /**
     * Updates an existing GrcBooking model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
             
        $agents = ArrayHelper::map(\app\modules\grc\models\GrcAgents::find()->where(['active'=>1])->orWhere('id=1')->all(), 'id', 'name');
        $rooms = ArrayHelper::map(\app\models\Rooms::find()->where(['deleted'=>0])->all(), 'id', 'name');

        $invoice = \app\modules\inventory\models\InvnInvoice::find()->where(['booking_id'=>$id, 'deleted'=>0])->one();
        $available_packages = \app\modules\grc\models\GrcPackage::getAvailableRoomPackagesByRoom($model->reservation->room->attributes['id']);
                
        
        $data = array(
                'reservation_data'=>$model->reservation->attributes,
                'room_data'=>$model->reservation->room->attributes,
                'booking_data'=>$model->attributes,
                'guest_data'=>$model->guest->attributes,
                'date_allocation' => GrcUtilities::computeDatesAllocation($model->reservation->attributes['start'], $model->reservation->attributes['end']),
                'available_room_packages' => \app\modules\grc\models\GrcPackage::getAvailableRoomPackagesByRoom($model->reservation->room->attributes['id'])
            );
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //var_dump($model->getErrors());
            //return $this->redirect(['view', 'id' => $model->id]);
            
            if(Yii::$app->request->isAjax){
                $this->renderJson(['result'=>'success', 'message'=>'Success', 'data'=>$data]);
                Yii::$app->end();
            }
                
                
            Yii::$app->session->setFlash('success', 'Success');    
            return $this->redirect(['view', 'id' => $model->id]);
                 
        } else {
            return $this->render('update', [
                'model' => $model,'agents'=>$agents,
                'rooms'=>$rooms, 'invoice'=>$invoice,
                'available_packages'=>  json_decode($available_packages),
                'data'=>$data,
            ]);
        }
    }

    /**
     * Deletes an existing GrcBooking model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the GrcBooking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GrcBooking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GrcBooking::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionConfirm()
    {
       
       if(Yii::$app->request->post()){
           
          $model = new GrcBooking();
                                    
          if($model->createInvoice(Yii::$app->request->post())){
      
            Yii::$app->session->setFlash('success', 'Success');
            return $this->redirect(Url::to(['booking/create']));
           
          }else{
              Yii::$app->session->setFlash('error', 'Failed');
              return $this->redirect(Url::to(['booking/create']));
          }                 
           
       }         
        
    }
    
    public function actionSearchReservations()
    {
      $request = Yii::$app->request->post();
      
      if(Yii::$app->request){
         
          $room = $request['room_id'][0];
          $connection = Yii::$app->getDb();
          
          #reservation with no booking
          #room_id
          #PENDING or CLOSED or deleted
          $sql = 'SELECT reservations.*, grc_booking.status
                  FROM reservations
                  LEFT JOIN grc_booking
                  ON grc_booking.reservation_id=reservations.id
                    WHERE grc_booking.reservation_id is null 
                  AND room_id="'.$room.'"  AND reservations.deleted = 0
                    OR grc_booking.status = "PENDING"
                    OR grc_booking.deleted <> 0';
                  
          $command = $connection->createCommand($sql);

          $result = $command->queryAll();
               
          $this->renderJSON(array('resv_data'=>$result, 'room_name'=>$request['room_label']));
      }  
    }
    
    public function actionFetchGuests()
    {
        $request = Yii::$app->request->get();
              
        $res = array();
	$q = strtolower($request['q']);
        if ($q) {
            $query = new Query;
            $query->select('*')->from('grc_guests')
                  ->andFilterWhere([ 
                                    'or',
                                    ['like', 'first_name', $q],
                                    ['like', 'last_name', $q],
                                  ])        
                   ->andWhere('deleted = 0');
            
            $res = array(
                'incomplete_results'=>false,
                'items'=>$query->all(),
                'total_count'=>100
                );
   
            $this->renderJSON($res);
      
	}
	
    }
    
    public function actionUpdatePackageInvItem()
    {
        if(Yii::$app->request->isAjax){
            $package_id = Yii::$app->request->post('package_id');
            $invitem_id = Yii::$app->request->post('invitem_id');
            
            $package = \app\modules\grc\models\GrcPackage::find()->andWhere(['id' => $package_id])->one();
            
            $invitem_model = \app\modules\inventory\models\InvnInvoiceItems::findOne($invitem_id);
            if($invitem_model->invoice->attributes['status'] == 'OPEN'){
                $invitem_model->package_id = $package_id;
                $invitem_model->price = $package->price;
                if($invitem_model->update()){
                    $this->renderJson(['result'=>'success', 'message'=>'success', 'data'=>array('invitem_id'=>$invitem_id)]);
                    
                }
            }else{
                $this->renderJson(['result'=>'fail', 'message'=>'Invoice is closed', 'data'=>[]]);
            }
           
        }
    }
    
    public function actionDashboard()
    {
        
        $searchModel = new \app\modules\grc\models\ViewBkRsvGstRmInvSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        $orderSearchModel = new \app\modules\inventory\models\ViewCustomerOrdersSearch;
        $orderDataProvider = $orderSearchModel->search(Yii::$app->request->queryParams);
        
        $currOccupents = GrcBooking::getCurrentOccupants();
        $items = \app\modules\inventory\models\InvnItemMaster::getItems();
    
        $stewards = InvnInvoice::getStewards();

       
        return $this->render('dashboard', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'currOccupents' => $currOccupents,
            'items'=>$items,
            'orderDataProvider'=> $orderDataProvider,
            'orderSearchModel'=>$orderSearchModel,'stewards'=>$stewards
        ]);
       
    }
    
    public function actionCheckResvAvailability()
    {
        $request = Yii::$app->request;
                
        $response = new \app\modules\grc\models\Reservations();
        
        $this->renderJson($response->datesConflict($request));                     
        
    }
    
    public function actionUpdateReservationDates()
    {
        $request = Yii::$app->request;
        
        $invoice_model = \app\modules\inventory\models\InvnInvoice::find()->where(['reservation_id'=>$request->post('reservation_id')])->one();
        $date_allocation = GrcUtilities::computeDatesAllocation($request->post('checkin'), $request->post('checkout'));
        
        #delete previous invoice items
        #create new invoice items
        #update reservation table
       
       $invnitems_model = \app\modules\inventory\models\InvnInvoiceItems::updateAll(
                ['deleted'=>1], 'invoice_id='.$invoice_model->attributes['id']);
       
      
       
       #insert new invoice item records 
       $data_batch = array();
      
       foreach (json_decode($date_allocation['date_allocation']) AS $key=>$value){
           $data_batch = array(
                   //'package_id' => $data["package_$i"],
                   'item_description'=> $value,
                   'package_id'=>1,
                   'invoice_id'=>$invoice_model->attributes['id'],
                   'item_master_id'=>1,
                   'price' => 0,
              
               );
           $insertCount = Yii::$app->db->createCommand()
                   ->insert('invn_invoice_items',$data_batch)->execute(); 
       }
       
       
       #update reservation 
       $data = array(
            'start'=>$request->post('checkin').Yii::$app->params['check_in_time'],
            'end'=>$request->post('checkout').Yii::$app->params['check_in_time'],
            'room_id'=>$request->post('room_id'),
           );
        
       $move = Yii::$app->db->createCommand()->update('reservations',$data,'id=:id', array(':id'=>$request->post('reservation_id')))->execute();
       
       if($move){
            $this->renderJson(['result'=>'success', 'message'=>'success', 'data'=>$move]);
            Yii::$app->end();
       }
       
       
       $this->renderJson(['result'=>'error', 'message'=>'success', 'data'=>$move]);
             
    }
    
    public function actionPlaceOrder()
    {
        if(Yii::$app->request->isAjax)
        {
            $invoiceModel = new \app\modules\inventory\models\InvnInvoice();
            $invoice_id = $invoiceModel->createInvoice(Yii::$app->request->post());
            if($invoice_id){
                $orders = \app\modules\inventory\models\ViewCustomerOrders::getOrdersByInvoiceId($invoice_id);
                
                foreach($orders AS $order)
                {
                    ob_start();
                    echo $this->renderPartial('@app/views/common/_order_item', ['order'=>$order]);
                    $htmlmarkup = ob_get_contents();
                    ob_end_clean();

                    $message = ['status' => 'OPEN', 'message' => $htmlmarkup]; 

                    $p = new PusherHelper();
                    
                    if($order->send_notification == 'KOT'){
                        $p->sendKotNotification($message, 'my_event');
                    }else{
                        $p->sendBotNotification($message, 'my_event');
                    }
                }
                
                $this->renderJson(['result'=>'success', 'message'=>'Success', 'data'=>'']);
                Yii::$app->end();
            }
                
                
          $this->renderJson(['result'=>'error', 'message'=>'Failed', 'data'=>'']);      
            
        }
        
    }
    
    public function actionFetchOrder()
    {
        if(Yii::$app->request->isAjax)
        {
          
            $data = InvnInvoiceItems::find()->where(['id'=>Yii::$app->request->post('ivoice_item_id')])->one();
            
            $this->renderJson(['result'=>'success', 'message'=>'Success', 'data'=>$data->attributes]);
        }
    }
    
    public function actionCancelOrderItem()
    {
        if(Yii::$app->request->isAjax)
        {
          
            $data = InvnInvoiceItems::cancelItem(Yii::$app->request->post('ivoice_item_id'));
            if($data){
                
                $message = ['status' => 'CANCEL', 'message' => json_encode($data->attributes)]; 
                $p = new PusherHelper();
                $p->sendKotNotification($message, 'my_event');
                
                $this->renderJson(['result'=>'success', 'message'=>'Success', 'data'=>$data]);
                Yii::$app->end();
            }
                
          $this->renderJson(['result'=>'error', 'message'=>'Failed', 'data'=>$data]);      
            
        }
    }

    public function actionTestPusher()
    {
        #https://dashboard.pusher.com/apps/267257/console/realtime_messages
        #https://dashboard.pusher.com/apps/267257/getting_started
        #https://pusher.com/docs/javascript_quick_start
        #https://github.com/pusher/pusher-http-php#push-notifications-beta
        /*$options = array(
            'encrypted' => true,
            'scheme'=>'http',
        );
        $pusher = new Pusher(
            'f61b79b5a60a9cf8df35',
            '3ad5caaab9e9b53bb5cd',
            '267257',
            $options
        );

        VarDumper::dump($pusher);

        $data['message'] = 'You have 1 new message';
        $x = $pusher->trigger('kot_channel', 'my_event', $data);
        var_dump($x);
        echo 'ok';*/
        
        
//        $message = ['status' => 'OPEN', 'message' => '<div class="line-item-box"><button type="button" class="btn btn-success lb-full" title="test order">test order
//                    <span class="badge">X 1</span></button></div>'];
        
        $order = \app\modules\inventory\models\ViewCustomerOrders::getOrderById(11);
        
        ob_start();
        echo $this->renderPartial('@app/views/common/_order_item', ['order'=>$order]);
        $htmlmarkup = ob_get_contents();
        ob_end_clean();
                
        $message = ['status' => 'OPEN', 'message' => $htmlmarkup]; 
        
        $p = new PusherHelper();
        $p->sendKotNotification($message, 'my_event');
    }
}
