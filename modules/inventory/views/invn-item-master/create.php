<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InvnItemMaster */

$this->title = 'Create Item';
$this->params['breadcrumbs'][] = ['label' => 'Invn Item Masters', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invn-item-master-create">

    <?= $this->render('_form', [
        'model' => $model,'supplier'=>$supplier,'category'=>$category
    ]) ?>

</div>
