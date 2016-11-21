<?php

namespace app\modules\inventory\controllers;

use app\modules\grc\models\GrcBooking;
use Yii;
use app\modules\inventory\models\PaymentReceiptMaster;
use app\modules\inventory\models\PaymentReceiptMasterSearch;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * ReceiptController implements the CRUD actions for PaymentReceiptMaster model.
 */
class ReceiptController extends \app\controllers\ApiController
{


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => [],
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete',
                        'payment-summary'],
                        'allow' => true,
                        //'roles' => ['@'],
                        'roles' => ['user-role'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all PaymentReceiptMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentReceiptMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PaymentReceiptMaster model.
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
     * Creates a new PaymentReceiptMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PaymentReceiptMaster();
        $model->receipt_date = date('Y-m-d');
        $model->pay_methods = 'CASH';
        $currOccupents = GrcBooking::getCurrentOccupants();



        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post();

            $model->pay_methods = json_encode(
                ['pay_method'=>$data['PaymentReceiptMaster']['pay_methods'],
                 'payment_details'=>$data['PaymentReceiptMaster']['payment_details']]);

        }


        if (Yii::$app->request->post() && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model, 'currOccupents'=>$currOccupents
            ]);
        }
    }

    /**
     * Updates an existing PaymentReceiptMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $currOccupents = GrcBooking::getCurrentOccupants();

        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            $data = Yii::$app->request->post();

            $model->pay_methods = json_encode(
                ['pay_method'=>$data['PaymentReceiptMaster']['pay_methods'],
                    'payment_details'=>$data['PaymentReceiptMaster']['payment_details']]);

        }

        if (Yii::$app->request->post() && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model, 'currOccupents'=>$currOccupents
            ]);
        }
    }

    /**
     * Deletes an existing PaymentReceiptMaster model.
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
     * Finds the PaymentReceiptMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PaymentReceiptMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PaymentReceiptMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPaymentSummary()
    {
        //$this->enableCsrfValidation = false;
        if(Yii::$app->request->isAjax)
        {
            $booking_id = Yii::$app->request->post('booking_id');
            $invoices = \app\modules\inventory\models\InvnInvoice::find()->where(['booking_id'=>$booking_id, 'status'=> 'OPEN', 'deleted'=>0])->all();
            $receipts = PaymentReceiptMaster::find()->where(['booking_id'=>$booking_id, 'is_cancelled'=>0, 'deleted'=>0])->asArray()->all();
            $booking = GrcBooking::find()->where(['id'=>$booking_id])->one();

            $page = Yii::$app->request->post('page');

            $data = [];
            $data['acomadation'] = [];
            $data['ordered_items'] = [];

            $acomadation_charges = 0; $order_item_charges = 0;
            foreach ($invoices AS $invoice){

                foreach($invoice->invnInvoiceItems AS $item) {
                    if($item->deleted == 0)
                    {
                        if($item->attributes['item_master_id'] ==1){
                            $data['acomadation'][] = $item->attributes;
                            $acomadation_charges += $item->attributes['price'];
                        }else{
                            $data['ordered_items'][] = $item->attributes;
                            $order_item_charges += $item->attributes['price'];
                        }
                    }
                   // VarDumper::dump($item->attributes);
                }
            }
            $data['booking_data'] = [
                'booking_id'=>$booking->id,
                'guest' =>  $booking->guest->FullName,
                'checkin' => $booking->reservation->start,
                'checkout' => $booking->reservation->end,
                'room' => $booking->reservation->room->name
            ];

            $data['receipt_total'] = 0;

            if(!empty($receipts)){
                foreach($receipts AS $receipt)
                {
                    $data['receipt_total'] += $receipt['amount_paid'];
                }
            }

            $data['receipts'] = $receipts;
            $data['acomadation_charges'] = $acomadation_charges;
            $data['order_item_charges'] = $order_item_charges;

            ob_start();
            echo $this->renderPartial($page, ['data'=>$data]);
            $htmlmarkup = ob_get_contents();
            ob_end_clean();

            $data['htmlmarkup'] = $htmlmarkup;


            $this->renderJson(['result'=>'success', 'message'=>'', 'data'=>$data]);
        }

    }
}
