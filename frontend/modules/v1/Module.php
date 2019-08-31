<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/5/18
 * Time: 12:08
 */

namespace frontend\modules\v1;
use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\v1\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        Yii::$app->user->enableSession = false;
    }
}