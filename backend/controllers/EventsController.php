<?php

namespace backend\controllers;

use common\models\Opus;
use common\models\OpusSearch;
use Yii;
use common\models\Events;
use common\models\EventsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * EventsController implements the CRUD actions for Events model.
 */
class EventsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Events models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EventsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Events model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Events model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Events();

        $params = Yii::$app->request->post();
        if ($model->load($params) ) {
            $model->pic = $model->pic[0];
            $model->icon = $model->icon[0];
//            $model->scan = $model->scan[0];
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Events model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $params = Yii::$app->request->post();
        if($model->load($params)){
            if($model->getOldAttribute('pic')  !== $model->pic){
                $model->pic = $model->pic[0];
            }
            if($model->getOldAttribute('icon')  !== $model->icon){
                $model->icon = $model->icon[0];
            }
//            if($model->getOldAttribute('scan')  !== $model->scan){
//                $model->scan = $model->scan[0];
//            }
            $change = false;
            if($model->getOldAttribute('status')==0 && $model->status == 1){
                $change = true;
                if(Events::isAlive()){
                    $model->status = 0;
                    $act = 'index';
                    echo "<script language=javascript>alert('当前有在线的活动请下架后上线!');</script>";
                }else if (!$model->hasOpus($model->id)) {
                    $model->status = 0;
                    if(strtoupper(substr(PHP_OS,0,3))==='WIN'){
                        $act = '..\opus\batchi';
                    }else{
                        $act = '../opus/batchi';
                    }
                    echo "<script language=javascript>alert('当前活动请添加展示作品后上线!');</script>";
                }
            }
            if ($model->save()) {
                if($change && $model->status == 0){
                    if($act == 'index'){
                        $searchModel = new EventsSearch();
                    }else{
                        $searchModel = new OpusSearch();
                    }
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                    return $this->render($act, [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                    ]);
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Events model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Events model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Events the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Events::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
