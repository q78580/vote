<?php
namespace common\widgets\multi;
use yii\web\AssetBundle;
/**
 * @author yanghu <127802495@qq.com>
 */
class QiniuInputVueAsset extends AssetBundle
{
    public $sourcePath = '../../vendor/colee/yii2-vue/assets/vue/dist';

    public $js = [
        'vue.js',
    ];
}