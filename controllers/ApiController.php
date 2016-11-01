<?php

namespace app\controllers;

use Yii;

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
    
    public function dd($var){
        echo '<pre>';
        \yii\helpers\VarDumper::dump($var);
        echo '</pre>';
        exit;
    }
}

?>
