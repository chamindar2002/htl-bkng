<?php

namespace app\modules\inventory\controllers;

use Yii;
use app\modules\inventory\models\InvnCategory;
use app\modules\inventory\models\InvnCategorySearch;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\modules\inventory\models\InvnDepartment;
/**
 * InvnCategoryController implements the CRUD actions for InvnCategory model.
 */
class InvnCategoryController extends Controller
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
     * Lists all InvnCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new InvnCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InvnCategory model.
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
     * Creates a new InvnCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new InvnCategory();
        $department = new InvnDepartment();
        $categories =  ArrayHelper::map($model->find()->where(['active'=>1])->orWhere(['id'=>1])->all(), 'id', 'name');
        $departments = ArrayHelper::map($department->find()->where(['active'=>1])->orWhere(['id'=>1])->all(), 'id', 'name'); 
        $model->stock_deductable =  0;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model, 'categories'=>$categories,'departments'=>$departments,
            ]);
        }
    }

    /**
     * Updates an existing InvnCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $categories =  ArrayHelper::map($model->find()->where(['active'=>1])->orWhere(['id'=>1])->all(), 'id', 'name');
        $departments = ArrayHelper::map($model->find()->where(['active'=>1])->orWhere(['id'=>1])->all(), 'id', 'name'); 

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //return $this->redirect(['view', 'id' => $model->id]);
            Yii::$app->session->setFlash('success', 'Success');
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', 'Error');
            return $this->render('update', [
                'model' => $model, 'categories'=>$categories, 'departments'=>$departments,
            ]);
        }
    }

    /**
     * Deletes an existing InvnCategory model.
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
     * Finds the InvnCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InvnCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InvnCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
