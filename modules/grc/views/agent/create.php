<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\grc\models\GrcAgents */

$this->title = 'Create Agents';
$this->params['breadcrumbs'][] = ['label' => 'Grc Agents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="grc-agents-create">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
