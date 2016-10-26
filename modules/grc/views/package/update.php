<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcPackage */

$this->title = 'Update Package: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Grc Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="grc-package-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model, 'rooms'=>$rooms, 'meal_plans'=>$meal_plans
    ]) ?>

</div>
