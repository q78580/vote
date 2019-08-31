<?php

namespace frontend\modules\v1\controllers;

use common\models\Events;
use fnsoxt\MpAuth;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\filters\VerbFilter;
use Yii;

class ClassesController extends ActiveController
{
    public $modelClass = 'common\models\Classet';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['cors_origin'],
                'Access-Control-Allow-Credentials' => true,
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => MpAuth::className(),
            'only' => [
                ''
            ],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                ''
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();


        $actions['index']['prepareDataProvider'] = function($action){
            $modelClass = $this->modelClass;
            $event = Events::find()->where(['status'=>1])->limit(1)->one();
            if(!$event){
                $event_id = 0;
            }else{
                $event_id = $event->id;
            }
            $query = $modelClass::find()->where(['event_id'=>$event_id])->orderBy(['id'=>SORT_DESC]);
            return new ActiveDataProvider([
                'query' => $query,
            ]);
        };

        return [
            'index' => $actions['index'],
            'options' => $actions['options'],
        ];
    }


}
