<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcMealPlan */

$this->title = 'Create Meal Plan';
$this->params['breadcrumbs'][] = ['label' => 'Grc Meal Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grc-meal-plan-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
