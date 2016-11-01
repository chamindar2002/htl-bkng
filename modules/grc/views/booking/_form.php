<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcBooking */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grc-booking-form">

    <?php $form = ActiveForm::begin(['id'=>$model->formName()]); ?>

    <div id="reservation_summary"></div>
    <?= $form->field($model, 'reservation_id')->textInput() ?>
    
    <?php if($model->isNewRecord){ ?>
    <?= $form->field($model, 'guest_name')->dropDownList([], ['prompt'=>'', 'class'=>'js-data-example-ajax']); ?> 
    <?php }else{ ?>
    <?= $form->field($model, 'guest_name')->dropDownList([$model->guest->getFullName()], ['class'=>'js-data-example-ajax']); ?> 
    <?php } ?>
    
    <?= $form->field($model, 'guest_id')->textInput(['readonly'=>'readonly']); ?>        
   
    <?= $form->field($model, 'agent_id')
        ->dropDownList($agents, ['prompt'=>'']); ?>

    <?= $form->field($model, 'no_of_adults')->textInput(['type'=>'number','min'=>'1', 'max'=>'5']) ?>

    <?= $form->field($model, 'no_of_children')->textInput(['type'=>'number','min'=>'0', 'max'=>'5']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php if(!$model->isNewRecord){ ?>
            <button type="button" class="btn btn-link" id="open_update_items_modal">Update Package</button>
        <?php } ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


