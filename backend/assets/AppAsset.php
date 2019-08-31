<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'statics/css/bootstrap.min.css',
        'statics/css/bootstrap-reset.css',
        'statics/assets/font-awesome/css/font-awesome.css',
        'statics/css/style.css',
        'statics/css/style-responsive.css',
    ];
    public $js = [
        'statics/js/jquery.dcjqaccordion.2.7.js',
        'statics/js/jquery.scrollTo.min.js',
        'statics/js/jquery.nicescroll.js',
        'statics/js/jquery.sparkline.js',
        'statics/js/slidebars.min.js',
        'statics/js/common-scripts.js',
        'adminlte/js/AdminLTE/showPreview.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
