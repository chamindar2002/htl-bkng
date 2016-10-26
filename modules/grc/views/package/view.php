<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcPackage */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Grc Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grc-package-view">

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
            'room_id',
            'meal_plan_id',
            'price',
            'active',
            'created_by',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
