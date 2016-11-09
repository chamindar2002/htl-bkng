<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\GrcUtilities;
/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InvnCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invn-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList($categories, ['class'=>'form-control']); ?>
    
    <?= $form->field($model, 'department_id')->dropDownList($departments, ['class'=>'form-control']); ?>
    
    <?= $form->field($model, 'send_notification')->dropDownList(GrcUtilities::getOrderPlacementFlag(), ['class'=>'form-control']); ?>

    <?= $form->field($model, 'stock_deductable')->radioList([
        '1' => 'Yes',
        '0' => 'No',
    ]); ?>

<!--    --><?//= $form->field($model, 'active')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'deleted')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'created_by')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'created_at')->textInput() ?>
<!---->
<!--    --><?//= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
