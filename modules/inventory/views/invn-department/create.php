<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\inventory\models\InvnDepartment */

$this->title = 'Create Department';
$this->params['breadcrumbs'][] = ['label' => 'Invn Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invn-department-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
