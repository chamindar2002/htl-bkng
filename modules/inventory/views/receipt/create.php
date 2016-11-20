<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\PaymentReceiptMaster */

$this->title = 'Create Receipt';
$this->params['breadcrumbs'][] = ['label' => 'Payment Receipt Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-receipt-master-create">


    <?= $this->render('_form', [
        'model' => $model, 'currOccupents'=>$currOccupents
    ]) ?>

</div>
