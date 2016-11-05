<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        
        <link href="<?= Yii::getAlias('@web') ?>/sbadmin/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        
        <link href="<?= Yii::getAlias('@web') ?>/sbadmin/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        
        <script src="<?= Yii::getAlias('@web') ?>/js/jquery-1.9.1.min.js" type="text/javascript"></script>   
     
        <script src="<?= Yii::getAlias('@web') ?>/js/daypilot-all.min.js"></script>
        <?php $this->head() ?>
    </head>
    <body>
    <?php  foreach (Yii::$app->session->getAllFlashes() as $key => $message): ?>

        <div class="alert alert-<?= $key ?>" >
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <?= $message ?>
        </div>

    <?php endforeach; ?>
 
    <?php $this->beginBody() ?>
        
     <?= $content ?>    
        
   
    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>