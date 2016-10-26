<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcPackage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grc-package-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'room_id')
        ->dropDownList($rooms, ['prompt'=>'']); ?>

    <?= $form->field($model, 'meal_plan_id')
        ->dropDownList($meal_plans, ['prompt'=>'']); ?>

    <?= $form->field($model, 'price')->textInput() ?>

    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
