<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\PaymentReceiptMaster */

$this->title = "Receipt #: ".$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Payment Receipt Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="form-group" style="width:500px !important;" id="receipt-printable-area">
    <table width="100%">
        <tr>
            <td><strong>Receipt Number </strong></td>
            <td><strong><?= $model->id ?></strong></td>
        </tr>
        <tr>
            <td><strong>Received Date</strong></td>
            <td><?= date('Y-m-d',strtotime($model->receipt_date)) ?></td>
        </tr>
        <tr>
            <td>Guest</td>
            <td><?= $model->booking->guest->FullName ?></td>
        </tr>
        <tr>
            <td>Booking</td>
            <td><?= date('Y-m-d',strtotime($model->booking->reservation->start)) ?> - <?= date('Y-m-d',strtotime($model->booking->reservation->end)) ?></td>
        </tr>
        <tr>
            <td>Booking Number</td>
            <td><?= $model->booking->id ?></td>
        </tr>

    </table>
    <br />
    <p>
    <table  class="table table-hover">
        <tr>
            <th>Receipt Number</th>
            <th>Receipt Details</th>
            <th>Recieved Amount</th>
        </tr>
        <tr>
            <td><?= $model->id ?></td>
            <td>
                <?php $payment_data = json_decode($model->pay_methods); ?>
                <?= $payment_data->pay_method ?> <?= $payment_data->payment_details != "" ?  $payment_data->payment_details : '' ?>
            </td>
            <td class="text-right"><?= number_format($model->amount_paid,2) ?></td>
        </tr>

    </table>
    </p>

    <p><span class="text-muted">created by : <?= $model->user->username ?></span></p>

</div>

<div class="payment-receipt-master-view">

    <p>
        <?= Html::a('<i class="glyphicon glyphicon-print"></i>', ['#/'], ['class' => 'btn btn-primary', 'onClick'=>'printeReceipt()']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php /*DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'booking_id',
            'receipt_date',
            'amount_paid',
            'is_cancelled',
            'deleted',
            'cancelled_by',
            'created_by',
            'created_at',
            'updated_at',
        ],
    ]) */ ?>

</div>

<script>
    function printeReceipt() {
        event.preventDefault();

        var prntstyle = '<link rel="stylesheet" href="<?= Yii::getAlias('@web') ?>/sbadmin/bootstrap/css/bootstrap.min.css" />';

        w=window.open(null, 'Print_Page', 'scrollbars=yes');
        w.document.write(prntstyle + jQuery('#receipt-printable-area').html());
        w.document.close();
        w.print();
    }
</script>
