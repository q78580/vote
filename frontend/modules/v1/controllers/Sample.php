<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/5/18
 * Time: 12:13
 */
namespace frontend\modules\v1\controllers;

use yii\data\ActiveDataProvider;
use yii\rest\ActiveController;
use yii\filters\auth\QueryParamAuth;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Yii;

class MatchController extends ActiveController
{
    public $modelClass = 'common\models\Match';

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
            'class' => QueryParamAuth::className(),
            'only' => ['index','create','delete','share'],
        ];
        $behaviors['verbs'] = [
            'class' => VerbFilter::className(),
            'actions' => [
                'edit' => ['put','options'],
            ],
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['prepareDataProvider'] = function($action){
            $modelClass = $this->modelClass;
            $query = $modelClass::find()->where(['user_id'=>Yii::$app->user->id])->orderBy(['id'=>SORT_DESC])->with('user.profile');
            return new ActiveDataProvider([
                'query' => $query,
            ]);
        };

        $actions['delete']['findModel'] = function($id,$action){
            $modelClass = $this->modelClass;

            $model = $modelClass::find()->where(['user_id'=>Yii::$app->user->id,'id'=>$id])->one();
            if ($model)
                return $model;
            else
                throw new NotFoundHttpException("Object not found: $id");
        };

        return [
            'create' => $actions['create'],
            'index' => $actions['index'],
            'view' => $actions['view'],
            'delete' => $actions['delete'],
        ];
    }

    public function actionList(){
        $user_id = Yii::$app->request->get('user_id');
        if(empty($user_id))
            throw new NotFoundHttpException("Object not found");

        $modelClass = $this->modelClass;
        $query = $modelClass::find()->where(['user_id'=>$user_id])->orderBy(['id'=>SORT_DESC])->with('user.profile');
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function actionShare(){
        $modelClass = $this->modelClass;
        $post_id = Yii::$app->request->post('post_id');

        $model = $modelClass::find()->where(['user_id'=>Yii::$app->user->id,'id'=>$post_id])->one();

        if ($model){
            $model->updateAttributes(['is_share'=>1]);
            return $model;
        }
        else
            throw new NotFoundHttpException("Object not found: $id");
    }
}
