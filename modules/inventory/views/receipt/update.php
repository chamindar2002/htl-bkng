<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\PaymentReceiptMaster */

$this->title = 'Update Receipt: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payment Receipt Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payment-receipt-master-update">

    <?= $this->render('_form', [
        'model' => $model, 'currOccupents'=>$currOccupents
    ]) ?>

</div>
