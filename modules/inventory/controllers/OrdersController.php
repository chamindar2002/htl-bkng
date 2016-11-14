<?php

namespace app\modules\inventory\controllers;

use Yii;
use app\modules\inventory\models\ViewCustomerOrders;

class OrdersController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $orders = ViewCustomerOrders::getOrders();
        return $this->render('index',['orders'=>$orders]);
    }

}
