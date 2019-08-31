<?php
use common\widgets\Menu;

echo Menu::widget(
    [
        'options' => [
            'class' => 'sidebar-menu'
        ],
        'items' => [
//            [
//                'label' => Yii::t('app', 'Dashboard'),
//                'url' => Yii::$app->homeUrl,
//                'icon' => 'fa-dashboard',
//                'active' => Yii::$app->request->url === Yii::$app->homeUrl
//            ],
//            [
//                'label' => Yii::t('app', '用户'),
//                'url' => ['/profile/index'],
//                'icon' => 'fa fa-user',
//            ],
//            [
//                'label' => Yii::t('app', '测量增长速度'),
//                'url' => ['/speed/index'],
//                'icon' => 'fa fa-star',
//            ],
            [
                'label' => Yii::t('app', '活动列表'),
                'url' => ['/events/index','sort'=>'-id'],
                //'visible' => (Yii::$app->user->identity->username == 'admin'),
                'icon' => 'fa fa-leaf',
            ],
            [
                'label' => Yii::t('app', '作品类型管理'),
                'url' => ['/classet/index'],
                //'visible' => (Yii::$app->user->identity->username == 'admin'),
                'icon' => 'fa fa-tags',
            ],
            [
                'label' => Yii::t('app', '投票详情'),
                'url' => ['/log/index'],
                'icon' => 'fa fa-align-left',
            ],
            [
                'label' => Yii::t('app', '作品列表'),
                'url' => ['/opus/index',],
                //'visible' => (Yii::$app->user->identity->username == 'admin'),
                'icon' => 'fa fa-camera',
            ],
        ],



    ]
);