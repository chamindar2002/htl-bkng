<?php

namespace app\modules\inventory\controllers;

use Yii;
use app\modules\inventory\models\InvnItemMaster;
use app\modules\inventory\models\InvnItemMasterSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\inventory\models\InvnSupplier;
use yii\helpers\ArrayHelper;
use app\modules\inventory\models\InvnCategory;
/**
 * InvnItemMasterController implements the CRUD actions for InvnItemMaster model.
 */
class InvnItemMasterController extends Controller
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
     * Lists all InvnItemMaster models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvnItemMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InvnItemMaster model.
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
     * Creates a new InvnItemMaster model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InvnItemMaster();
        
        $supplier =  ArrayHelper::map(InvnSupplier::find()->where(['active'=>1])->orWhere(['id'=>1])->all(), 'id', 'name');
        $category =  ArrayHelper::map(InvnCategory::find()->where(['active'=>1])->orWhere(['deleted'=>0])->all(), 'id', 'name');
       
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model, 'supplier'=>$supplier, 'category'=>$category
            ]);
        }
    }

    /**
     * Updates an existing InvnItemMaster model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $supplier =  ArrayHelper::map(InvnSupplier::find()->where(['active'=>1])->orWhere(['id'=>1])->all(), 'id', 'name');
        $category =  ArrayHelper::map(InvnCategory::find()->where(['active'=>1])->orWhere(['deleted'=>0])->all(), 'id', 'name');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model, 'supplier'=>$supplier, 'category'=>$category,
            ]);
        }
    }

    /**
     * Deletes an existing InvnItemMaster model.
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
     * Finds the InvnItemMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InvnItemMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InvnItemMaster::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionAutoCompleteItem()
    {
        $d = array(
            ['id'=>100,'full_name'=>'Just in case'],
            ['id'=>101,'full_name'=>'Kidding me'],
        );
        
        
        echo json_encode($d);
    }
}
