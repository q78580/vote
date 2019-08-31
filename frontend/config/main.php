<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['debug','log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'v1' => [
            'class' => 'frontend\modules\v1\Module',
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'weixinjs' => [
            'class' => 'fnsoxt\weixinjs\WeixinJs',
            'appId' => '',
            'appSecret' => '',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'weixin' => [
                    'class' => 'xj\oauth\WeixinAuth',
                    'clientId' => '',
                    'clientSecret' => '',
                    'authUrl' => 'https://open.weixin.qq.com/connect/oauth2/authorize',
                    'scope' => 'snsapi_userinfo',
                ],
                'weixin_basic' => [
                    'class' => 'xj\oauth\WeixinAuth',
                    'clientId' => '',
                    'clientSecret' => '',
                    'authUrl' => 'https://open.weixin.qq.com/connect/oauth2/authorize',
                    'scope' => 'snsapi_base',
                ],
                'xiaochengxu' => [
                    'class' => 'xj\oauth\WeixinMpAuth',
                    'clientId' => '',
                    'clientSecret' => '',
                    'authUrl' => 'https://api.weixin.qq.com/sns/jscode2session',
                    'scope' => 'snsapi_userinfo',
                ],
            ]
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/default', 'pluralize'=>false],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/user', 'pluralize'=>false,'extraPatterns'=>['OPTIONS <action>' => 'options']],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/classes', 'pluralize'=>false,'extraPatterns'=>['OPTIONS <action>' => 'options']],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/opus', 'pluralize'=>false,'extraPatterns'=>['OPTIONS <action>' => 'options']],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'v1/vote', 'pluralize'=>false,'extraPatterns'=>['OPTIONS <action>' => 'options']],
            ],
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
        'request' => [
            'parsers' => [
                'text/xml' => 'components\XmlParser',
                'application/xml' => 'components\XmlParser',
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
