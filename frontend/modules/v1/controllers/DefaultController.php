<?php

namespace frontend\modules\v1\controllers;

use backend\controllers\RecordController;
use common\models\_PercentData;
use common\models\Events;
use common\models\Person;
use common\models\Record;
use common\models\Setting;
use common\models\Speed;
use fnsoxt\MpAuth;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use Yii;

class DefaultController extends ActiveController
{
    public $modelClass = 'common\models\Null';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['跨域所需的前端url'],
                'Access-Control-Allow-Credentials' => true,
            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'event' => ['get','options'],
                'weixinjs' => ['post','options']
            ],
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        return [
        ];
    }


    public function actionWeixinjs()
    {
        $weixinjs = Yii::$app->weixinjs;
        $url = Yii::$app->request->post('url');
        Yii::info($url);
        if($url)
            $weixinjs->url = $url;
        $weixin_sign = $weixinjs->getSignPackage();
        Yii::info($weixin_sign);
        return $weixin_sign;
    }

    public function actionEvent(){
        $events = Events::find()->where(['status'=>1])->one();
        if($events){
            unset($events->fake_view_num);
            unset($events->fake_voting_num);
            unset($events->report_deadline);
            unset($events->report_time);
            unset($events->user_max);
            unset($events->ip_max);
        }
        return $events;
    }
}
