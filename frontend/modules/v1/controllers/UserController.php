<?php

namespace frontend\modules\v1\controllers;

use common\models\Predict;
use common\models\Profile;
use fnsoxt\MpAuth;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\filters\VerbFilter;
use Yii;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['cors_origin'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'OPTIONS'],
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => MpAuth::className(),
            'only' => ['userinfo','predict','view','profile','decrypt'],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'userinfo' => ['get','options'],
                'edit' => ['put','options'],
                'predict' => ['post','options'],
                'profile' => ['post','options'],
                'findpredict' => ['get','options'],
            ],
        ];

        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

//        $actions['view']['findModel'] = function($id,$action){
//            $modelClass = $this->modelClass;
//
//            $model = $modelClass::findOne($id);
//            unset($model->profile->phone);
//            unset($model->profile->username);
//            if ($model)
//                return $model;
//            else
//                throw new NotFoundHttpException("Object not found: $id");
//        };

        return [
//            'view' => $actions['view'],
            'options' => $actions['options'],
        ];
    }
}
