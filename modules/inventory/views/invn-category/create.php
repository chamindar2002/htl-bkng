<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InvnCategory */

$this->title = 'Create Category';
$this->params['breadcrumbs'][] = ['label' => 'Invn Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invn-category-create">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model, 'categories'=>$categories
    ]) ?>

</div>
