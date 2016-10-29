<?php

namespace app\modules\grc\controllers;

use Yii;
use app\modules\grc\models\GrcBooking;
use app\modules\grc\models\BookingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\components\GrcUtilities;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\db\Query;

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
                        'actions' => ['index', 'view', 'create', 'update', 'delete','confirm', 'search-reservations', 'fetch-guests'],
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
        return $this->render('view', [
            'model' => $this->findModel($id),
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
       
        $guests = ArrayHelper::map(\app\modules\grc\models\GrcGuest::find()->where(['deleted'=>0])->all(), 'id', 'first_name');
        $agents = ArrayHelper::map(\app\modules\grc\models\GrcAgents::find()->where(['active'=>1])->all(), 'id', 'name');
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
            
            $this->renderJson(['result'=>'success', 'message'=>'Success', 'data'=>$data]);
            
        } else {
           
            //var_dump($model->getErrors());
            if(!empty($model->getErrors()) && Yii::$app->request->isAjax)
                $this->renderJson(['result'=>'fail', 'message'=>'Fail', 'data'=>$model->getErrors()]);
            
            return $this->render('create', [
                'model' => $model,'guests'=>$guests, 'agents'=>$agents, 'rooms'=>$rooms
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
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
           
          }                 
           
       }         
        
    }
    
    public function actionSearchReservations()
    {
      $request = Yii::$app->request->post();
      
      if(Yii::$app->request){
          $query = new Query;
          $query->select('*')->from('reservations')
                  ->where('room_id = '.$request['room_id'][0])
                  ->where('status <> "CheckedOut"');
          
                
          $result = array('resv_data'=>$query->all(), 'room_name'=>$request['room_label']);
        
          $this->renderJSON($result);
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
            
            //var_dump($query->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
            //exit();

            $this->renderJSON($res);
      
	}
	
    }
}
