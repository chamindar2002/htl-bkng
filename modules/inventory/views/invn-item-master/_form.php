<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\GrcUtilities;
/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InvnItemMaster */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="invn-item-master-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'category_id')->dropDownList($category, ['prompt'=>'', 'class'=>'form-control']); ?>

    <?= $form->field($model, 'supplier_id')->dropDownList($supplier, ['class'=>'form-control']); ?>
    
    <?= $form->field($model, 'unit_id')->dropDownList(['1'=>'NONE','2'=>'GRAMS (g)'], ['class'=>'form-control']); ?>
    
    <?= $form->field($model, 'reoder_point')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'opening_stock')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'active')->dropDownList(GrcUtilities::getRecordStatus(), ['class'=>'form-control']); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
