<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcMealPlan */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Grc Meal Plans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grc-meal-plan-view">

    <h3><?= Html::encode($this->title) ?></h3>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'deleted',
            'created_by',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
