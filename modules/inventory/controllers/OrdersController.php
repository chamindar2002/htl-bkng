<?php

namespace app\modules\inventory\controllers;

use Yii;
use app\modules\inventory\models\ViewCustomerOrders;
use app\controllers\ApiController;
use app\modules\inventory\models\ViewCustomerOrdersSearch;

class OrdersController extends ApiController
{
    public function actionIndex()
    {
        $orders = ViewCustomerOrders::getOrders();
        return $this->render('index',['orders'=>$orders]);
    }

    public function actionFetchOrder()
    {
        $order = ViewCustomerOrdersSearch::find()->where(['invoice_item_id'=>Yii::$app->request->post('order_id')])->one();

        $htmlmarkup = $this->renderPartial('_print', ['order'=>$order]);


        $this->renderJson(['result'=>'success','html'=>$htmlmarkup]);
    }

}
