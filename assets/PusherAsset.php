<?php
/**
 * Created by PhpStorm.
 * User: Oracle
 * Date: 11/15/2016
 * Time: 6:15 AM
 */

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

class PusherAsset extends AssetBundle
{

    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public function init() {
        $this->jsOptions['position'] = View::POS_BEGIN;
        parent::init();
    }

    public $css = [

    ];
    public $js = [
        'js/pusher.min.js',

    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}