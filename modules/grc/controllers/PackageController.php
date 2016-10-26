<?php

namespace app\modules\grc\controllers;

use Yii;
use app\modules\grc\models\GrcPackage;
use app\modules\grc\models\PackageSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * PackageController implements the CRUD actions for GrcPackage model.
 */
class PackageController extends Controller
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
     * Lists all GrcPackage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PackageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single GrcPackage model.
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
     * Creates a new GrcPackage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new GrcPackage();
        
        
        $rooms = ArrayHelper::map(\app\models\Rooms::find()->where(['deleted'=>0])->all(), 'id', 'name');
        $meal_plans = ArrayHelper::map(\app\modules\grc\models\GrcMealPlan::find()->where(['deleted'=>0])->all(), 'id', 'name');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Success');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            //var_dump($model->getErrors());exit;
            return $this->render('create', [
                'model' => $model, 'rooms'=>$rooms, 'meal_plans'=>$meal_plans
            ]);
        }
    }

    /**
     * Updates an existing GrcPackage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        $rooms = ArrayHelper::map(\app\models\Rooms::find()->where(['deleted'=>0])->all(), 'id', 'name');
        $meal_plans = ArrayHelper::map(\app\modules\grc\models\GrcMealPlan::find()->where(['deleted'=>0])->all(), 'id', 'name');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            //var_dump($model->getErrors());exit;
            return $this->render('update', [
                'model' => $model, 'rooms'=>$rooms, 'meal_plans'=>$meal_plans
            ]);
        }
    }

    /**
     * Deletes an existing GrcPackage model.
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
     * Finds the GrcPackage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return GrcPackage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GrcPackage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
