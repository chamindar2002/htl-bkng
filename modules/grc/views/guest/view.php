<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcGuest */

$this->title = $model->title.'. '.$model->first_name. ' '. $model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Grc Guests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grc-guest-view">

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
            'title',
            'first_name',
            'last_name',
            'address',
            'post_code',
            'city',
            'country',
            'phone',
            'email:email',
            'nationality',
            'identification',
            'deleted',
            'created_by',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
