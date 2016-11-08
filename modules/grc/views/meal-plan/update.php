<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcMealPlan */

$this->title = 'Update Meal Plan: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Grc Meal Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="grc-meal-plan-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
