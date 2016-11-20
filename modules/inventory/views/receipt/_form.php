<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\web\View;
use app\assets\DpAsset;
use app\assets\Select2Asset;
use yii\jui\AutoComplete;

DpAsset::register($this);
Select2Asset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\PaymentReceiptMaster */
/* @var $form yii\widgets\ActiveForm */
$url = \yii\helpers\Url::to(['city-list']);
?>

<div class="payment-receipt-master-form">


    <?php $form = ActiveForm::begin(); ?>


    <div class="form-group">
        <label>Room</label>
        <?= AutoComplete::widget([
            'value'=>isset($model->booking->reservation->room->name) ? $model->booking->reservation->room->name : '',
            'clientOptions' => [
                'source' => $currOccupents,
                'minLength'=>'1',
                'autoFill'=>true,
                'select' => new JsExpression("function( event, ui ) {
                    $('#paymentreceiptmaster-booking_id').val(ui.item.id);
                    $('#temp_guest').val(ui.item.full_name);
                 }")],
            'options' => [
                'class' => 'form-control', //single class
                'placeholder'=>'Type in room number',
                'id' => 'select2-room-search',

            ],
        ]);
        ?>
        <input type="hidden" id="temp_booking_id" value="">
        <input type="text" class="form-control" id="temp_guest" value="<?= isset($model->booking->guest->FullName) ? $model->booking->guest->FullName : '' ?>" readonly>
    </div>


    <?= $form->field($model, 'booking_id')->textInput(['readonly'=>'readonly']) ?>

    <?= $form->field($model, 'receipt_date')->widget(\yii\jui\DatePicker::classname(), [
        //'language' => 'ru',
        'options' => ['class' => 'form-control', 'readonly'=>'readonly' ],
        'dateFormat' => 'yyyy-M-dd',
    ]) ?>

    <?php
    if($model->isNewRecord) {
        $checked = $model->pay_methods;
    }else{
        $checked = json_decode($model->pay_methods)->pay_method;
    }
    //echo "$checked <br>";
    ?>

    <?php foreach(\app\components\GrcUtilities::getPayOptions() As $key=>$value){ ?>

        <input type="radio"
               name="PaymentReceiptMaster[pay_methods]"
               value="<?= $key ?>"
               <?= $key == $checked ? 'checked' : '' ?>
        > <?= $value ?><br />
    <?php } ?>



    <div class="form-group field-paymentreceiptmaster-amount_paid">
        <input type="text" name="PaymentReceiptMaster[payment_details]"
               class="form-control"
               value="<?= json_decode($model->pay_methods)->payment_details != '' ? json_decode($model->pay_methods)->payment_details : '' ?>"
               placeholder="card no, check no, tranfer no, etc">
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<div class="ui-widget">
    <label for="city">Your city: </label>
    <input id="city" class="form-control">
    Powered by <a href="http://geonames.org">geonames.org</a>
</div>

<div class="ui-widget" style="margin-top:2em; font-family:Arial">
    Result:
    <div id="log" style="height: 200px; width: 300px; overflow: auto;" class="ui-widget-content"></div>
</div>

<script>
    $(function() {
        function log( message ) {
            $( "<div>" ).text( message ).prependTo( "#log" );
            $( "#log" ).scrollTop( 0 );
        }

        $( "#city" ).autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: "http://gd.geobytes.com/AutoCompleteCity",
                    dataType: "jsonp",
                    data: {
                        q: request.term
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            minLength: 3,
            select: function( event, ui ) {
                log( ui.item ?
                "Selected: " + ui.item.label :
                "Nothing selected, input was " + this.value);
            },
            open: function() {
                $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
            },
            close: function() {
                $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
            }
        });
    });
</script>