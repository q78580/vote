<?php

namespace frontend\modules\v1\controllers;

use common\models\Events;
use common\models\Log;
use common\models\Opus;
use fnsoxt\MpAuth;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\filters\VerbFilter;
use Yii;

class VoteController extends ActiveController
{
    public $modelClass = 'common\models\Log';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => ['跨域所需的前端url'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Request-Headers' => ['Content-Type'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'OPTIONS'],
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => MpAuth::className(),
            'only' => [
                'vote'
            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'vote'=>['post','options']
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        return [
            'options' => $actions['options'],
        ];
    }

    public function actionVote(){
        $time = time();
        $opus_id = Yii::$app->request->post('opus_id');
        $opus = Opus::findOne($opus_id);
        $user = Yii::$app->user->identity;
        if(!$opus_id || !$opus || !$user){
            return ['code'=>0,'errMsg'=>'Data undefined'];
        }
        $event = Events::find()->where(['status'=>1])->limit(1)->one();

        if(!$event) {
            return ['code'=>0,'errMsg'=>'Event not found'];
        }
        if(isset($event->voting_time) && $time < $event->voting_time) {
            return ['code'=>0,'errMsg'=>'Event has not begun'];
        }
        if(isset($event->voting_deadline) && $time > $event->voting_deadline){
            return ['code'=>0,'errMsg'=>'Event has come to an end'];
        }

        $is_today = $this->validateTime($time);
        $ip = ip2long(\Yii::$app->request->userIP);

        //投票限制
        if($is_today){
            //每人每天
            if($event->user_max >= 1) {
                $user_count = Log::find()->where(['user_id'=>$user->id,'event_id'=>$event->id])->count();
                if($user_count >= $event->user_max){
                    return ['code'=>0,'errMsg'=>'The number of votes exceeds the limit'];
                }
            }
            //每个IP每天
            if($event->ip_max >= 1) {
                $ip_count = Log::find()->where(['ips'=>$ip,'event_id'=>$event->id])->count();
                if($ip_count >= $event->ip_max){
                    return ['code'=>0,'errMsg'=>'The number of votes exceeds the limit'];
                }
            }
        }

        $log = new Log();
        $log->user_id = $user->id;
        $log->event_id = $event->id;
        $log->opus_id = $opus_id;
        $log->created_at = $time;
        $log->remarkb = "".strtotime(date('Y-m-d',$time));//最后一次投票的日期
        $log->ips = "".$ip;
        if($log->save()){
            $opus->voting_num = $opus->voting_num + 1;
            $opus->update();
            return ['code'=>1,'Msg'=>'succeed!'];
        }
        return ['code'=>0,'errMsg'=>'Save error'];
    }

    private function validateTime($now_time){
        $last_log = Log::find()->orderBy(['created_at'=>SORT_DESC])->limit(1)->one();
        if(!$last_log || ($now_time - $last_log->remarkb >= 60*60*24 )){//如果没投过或距离上期间隔已超过一个自然日
            return false;
        }
        return true;
    }
}
