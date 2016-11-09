<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\GrcUtilities;

/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InvnDepartment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invn-department-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'active')->dropDownList(GrcUtilities::getRecordStatus(), ['class'=>'form-control']); ?>

    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
