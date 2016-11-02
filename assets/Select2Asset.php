<?php
namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Description of Select2Asset
 *
 * @author Oracle
 */
class Select2Asset extends AssetBundle{
    
    
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    
    public function init() {
        $this->jsOptions['position'] = View::POS_END;
        $this->cssOptions['position'] = View::POS_BEGIN;
        parent::init();
    }
    
    public $css = [
        //'css/select2.css',
    ];
    public $js = [
        'js/select2.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
    
}

?>
