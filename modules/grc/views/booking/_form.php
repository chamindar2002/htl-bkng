<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcBooking */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grc-booking-form">

    <?php $form = ActiveForm::begin(['id'=>$model->formName()]); ?>
     <?php if(!$model->isNewRecord){ ?>

         <div class="panel panel-default">
             <div class="panel-heading">Reservation</div>
             <div class="panel-body">
                 <table class="table table-condensed">
                     <tr><th>Check-in Date</th><th>Check-out Date</th><th>Room</th></tr>
                     <tr>
                         <td><?= $data['reservation_data']['start'] ?></td>
                         <td><?= $data['reservation_data']['end'] ?></td>
                         <td><?= $data['room_data']['name'] ?></td>
                     </tr>
                 </table>
             </div>
         </div>

         
     <?php } ?>

    <?php if($model->isNewRecord){ ?>
        <div id="reservation_summary"></div>
        <?= $form->field($model, 'reservation_id')->textInput() ?>
    <?php } ?>    

    <div class="row">
        <div class="col-xs-10">
            <?php if($model->isNewRecord){ ?>
                <?= $form->field($model, 'guest_name')->dropDownList([], ['prompt'=>'', 'class'=>'js-data-example-ajax']); ?>
            <?php }else{ ?>
                <?= $form->field($model, 'guest_name')->dropDownList([$model->guest->getFullName()], ['class'=>'js-data-example-ajax']); ?>
            <?php } ?>
        </div>
        <div class="col-xs-2">
            <button type="button" class="form-control btn btn-link" id="open_crete_guest_modal">Add new guest</button>
        </div>
    </div>

    <?= $form->field($model, 'guest_id')->textInput(['readonly'=>'readonly']); ?>

    <?= $form->field($model, 'agent_id')
        ->dropDownList($agents, []); ?>

    <?= $form->field($model, 'no_of_adults')->textInput(['type'=>'number','min'=>'1', 'max'=>'5']) ?>

    <?= $form->field($model, 'no_of_children')->textInput(['type'=>'number','min'=>'0', 'max'=>'5']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php if(!$model->isNewRecord){ ?>
                <button type="button" class="btn btn-link" id="open_update_items_modal">Update package</button>
                <button type="button" class="btn btn-link" id="open_update_dates_modal">Update Checkin/Checkout dates</button>
                <?= Html::a('View', ['view', 'id' => $model->id], ['class' => 'btn btn-link']) ?>
        <?php } ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>


