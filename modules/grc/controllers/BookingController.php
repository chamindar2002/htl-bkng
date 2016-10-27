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
/**
 * BookingController implements the CRUD actions for GrcBooking model.
 */
class BookingController extends \app\controllers\ApiController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
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
        
        $packages = ArrayHelper::map(\app\modules\grc\models\GrcPackage::find()->where(['active'=>1])->all(), 'id', 'mealPlan.name');
        $agents = ArrayHelper::map(\app\modules\grc\models\GrcAgents::find()->where(['active'=>1])->all(), 'id', 'name');
        //\yii\helpers\VarDumper::dump(Yii::$app->request->isAjax);exit();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Success');
            //return $this->redirect(['view', 'id' => $model->id]);
           
            $data = array(
                'reservation_data'=>$model->reservation->attributes,
                'room_data'=>$model->reservation->room->attributes,
                'booking_data'=>$model->attributes,
                'date_allocation' => GrcUtilities::computeDatesAllocation($model->reservation->attributes['start'], $model->reservation->attributes['end']),
            );
            
                       
            $this->renderJson(['result'=>'success', 'message'=>'Success', 'data'=>$data]);
            
        } else {
            //var_dump($model->getErrors());
            if(!empty($model->getErrors()) && Yii::$app->request->isAjax)
                $this->renderJson(['result'=>'fail', 'message'=>'Fail', 'data'=>$model->getErrors()]);
            
            return $this->render('create', [
                'model' => $model,'packages'=>$packages, 'agents'=>$agents
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
}
