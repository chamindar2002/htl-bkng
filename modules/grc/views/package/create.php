<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcPackage */

$this->title = 'Create Package';
$this->params['breadcrumbs'][] = ['label' => 'Grc Packages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grc-package-create">


    <?= $this->render('_form', [
        'model' => $model,'rooms'=>$rooms, 'meal_plans'=>$meal_plans
    ]) ?>

</div>
