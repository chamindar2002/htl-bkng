<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\GrcUtilities;
/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcGuest */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="grc-guest-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-xs-2">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-5">
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-5">
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'identification')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-xs-4">
            <?= $form->field($model, 'post_code')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-4">
            <?php $options = $model->isNewRecord ? ['options'=>['LK' => ['Selected'=>'selected']]] : [] ?>
            <?= $form->field($model, 'country')->dropDownList(GrcUtilities::countryList(), $options) ?>

        </div>
    </div>



    <div class="row">
        <div class="col-xs-4">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-xs-4">
            <?= $form->field($model, 'nationality')->textInput(['maxlength' => true]) ?>
        </div>
    </div>



    

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>