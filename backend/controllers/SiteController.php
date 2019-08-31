<?php
namespace backend\controllers;

use common\models\Person;
use common\models\Record;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index','reset','view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionView()
    {
        return $this->render('view');
    }

    public function actionLogin()
    {
        $this->layout = 'login.php';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionReset(){
        if(Yii::$app->user->id != 1){
            echo"<script language=javascript>alert('当前操作用户非管理员用户!');history.back();</script>";
        }
        $records = Record::find()->all();
        foreach($records as $key =>$value){
            $person = Person::findOne($value->person_id);
            if(!$person || $person->height === null || $person->status != 1){
                continue;
            }
            $result = Record::comment($person->gender, $value->height, $value->weight, $person->age);
            if ($result === false){
                continue;
            }
            $value->comment = $result[1];
            $value->result = $result[0];
            $value->updated_at = time();
            $value->save(false);
            $person->r_result = $value->result;
            $person->update(false);
        }
        echo"<script language=javascript>alert('操作成功!');history.back();</script>";
    }
}
