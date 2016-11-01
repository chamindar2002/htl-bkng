<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcGuest */

$this->title = 'Create Guest';
$this->params['breadcrumbs'][] = ['label' => 'Grc Guests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grc-guest-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
