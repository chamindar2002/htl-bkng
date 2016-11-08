<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcGuest */

$this->title = 'Update Guest: ' . $model->title.'. '.$model->first_name. ' '. $model->last_name;
$this->params['breadcrumbs'][] = ['label' => 'Grc Guests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="grc-guest-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
