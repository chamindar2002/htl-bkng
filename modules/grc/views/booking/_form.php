<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcBooking */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grc-booking-form">

    <?php $form = ActiveForm::begin(['id'=>$model->formName()]); ?>

    <?= $form->field($model, 'reservation_id')->textInput() ?>

    <?= $form->field($model, 'guest_id')
        ->dropDownList($guests, ['prompt'=>'']); ?>

    <?= $form->field($model, 'agent_id')
        ->dropDownList($agents, ['prompt'=>'']); ?>

    <?= $form->field($model, 'no_of_adults')->textInput() ?>

    <?= $form->field($model, 'no_of_children')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


