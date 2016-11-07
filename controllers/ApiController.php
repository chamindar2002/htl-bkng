<?php

namespace app\controllers;

use Yii;
use yii\db\Query;

class ApiController extends \yii\web\Controller{
    
    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }
    
     public function renderJson($data){
        $response = Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = $data;

        return $response;
        
        
    }

    protected function reservationIsBooked($reservation_id){
        $query = new Query;
        $result = $query->select('id, status')->from('grc_booking')->where('reservation_id ='.$reservation_id)->one();

        if(count($result)){
            if($result['status'] == 'OPEN' || $result['status'] == 'CLOSED'){

               $this->renderJson(array(
                   'result'=>'Error',
                   'message'=>'Error. A booking already exits. status : '.$result['status'],
               ));
                Yii::$app->end();

            }
        }

        return false;

    }
    
    public function dd($var){
        echo '<pre>';
        \yii\helpers\VarDumper::dump($var);
        echo '</pre>';
        exit;
    }
}

?>
