<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcAgents */

$this->title = 'Update Agent: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Grc Agents', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="grc-agents-update">

    <h3><?= Html::encode($this->title) ?></h3>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
