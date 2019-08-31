<?php

namespace frontend\modules\v1\controllers;

use common\models\Events;
use common\models\Opus;
use fnsoxt\MpAuth;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\filters\VerbFilter;
use Yii;

class OpusController extends ActiveController
{
    public $modelClass = 'common\models\Opus';

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
            $class_id = Yii::$app->request->get('class_id');
            $query = $modelClass::find();
            if($class_id){
                $query = $query->where(['event_id'=>$event_id,'type_id'=>$class_id,'is_display'=>1]);
            }else{
                $query = $query->where(['event_id'=>$event_id,'is_display'=>1]);
            }
            $query->orderBy(['created_at'=>SORT_DESC]);
            return new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
            ]);
        };

        $actions['view']['findModel'] = function($id,$action){
            $modelClass = $this->modelClass;

            $model = $modelClass::findOne($id);
            if ($model)
                return $model;
            else
                throw new NotFoundHttpException("Object not found: $id");
        };

        return [
            'index' => $actions['index'],
            'view' => $actions['view'],
//            'options' => $actions['options'],
        ];
    }

    public function actionRank(){
        $modelClass = $this->modelClass;
        $type = Yii::$app->request->get('class_id');
        $event = Events::find()->where(['status'=>1])->limit(1)->one();
        if(!$event){
            $event_id = 0;
        }else{
            $event_id = $event->id;
        }
        $query = $modelClass::find();
        if($type){
            $query = $query->where(['event_id'=>$event_id,'type_id'=>$type,'is_display'=>1]);
        }else{
            $query =  $query->where(['event_id'=>$event_id,'is_display'=>1]);
        }
        $query->orderBy(['voting_num'=>SORT_DESC]);
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);
    }
}
