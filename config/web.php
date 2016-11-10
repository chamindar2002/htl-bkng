<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'layout' => '@app/views/yii2-app/layouts/main',
    //'layout' => 'main',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '2cz_jF-GrMp3cxgnXmfGg6jOTTB3x8Ya',
        ],
        'grcutilities' => [
            'class' => 'app\components\GrcUtilities',
        ],
        'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-blue-light',#adminLte doc: https://github.com/dmstr/yii2-adminlte-asset, http://www.yiiframework.com/extension/yii2-adminlte-asset/
                ],
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'authManager'  => [
            'class'        => 'yii\rbac\DbManager', #https://www.youtube.com/watch?v=vLb8YATO-HU
        //            'defaultRoles' => ['guest'],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        
    ],
    'modules' => [
            'admin' => [
              'class' => 'mdm\admin\Module', 
            ],  
            'grc' => [
                'class' => 'app\modules\grc\GrcModule',
            ],
            'inventory' => [
                'class' => 'app\modules\inventory\InventoryModule',
            ],
         ],
    'as access'=>[
	'class'=>'mdm\admin\components\AccessControl',
	'allowActions'=>[
		'site/*',
	],
    ],
    'defaultRoute' => 'dashboard',
    'params' => $params,
    
    
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
