<?php

namespace backend\controllers;

use kartik\grid\EditableColumnAction;
use Yii;
use common\models\Opus;
use common\models\OpusSearch;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OpusController implements the CRUD actions for Opus model.
 */
class OpusController extends Controller
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

    public function actions()
    {
        return [
            'upload'=>[
                'class' => 'common\widgets\file_upload\UploadAction',
                'config' => [
                    'imagePathFormat' => "/upload/store/{yyyy}{mm}{dd}/{time}{rand:6}",
                ]
            ],
            'editproduct' => [
                'class' => EditableColumnAction::className(),
                'modelClass' => Opus::className(),
                'showModelErrors' => true,
            ]
        ];
    }
    /**
     * Lists all Opus models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OpusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (Yii::$app->request->post('hasEditable')) {
            $id = Yii::$app->request->post('editableKey');
            $model = Opus::findOne(['id' => $id]);
            $output = '';
            $posted = current($_POST['Opus']);
            $post = ['Opus' => $posted];
            if ($model->load($post)) {
                $model->save();
                isset($posted['user_id']) && $output = $model->user_id;
                isset($posted['summary']) && $output = $model->summary;
                isset($posted['is_check']) && $output = ($model->is_check === 0 ?'未审核':'已审核');
                isset($posted['is_display']) && $output = ($model->is_display === 0 ?'不显示':'显示');
                // 其他的这里就忽略了，大致可参考这个title
            }
            $out = Json::encode(['output'=>$output, 'message'=>'']);
            return $out;
        }

        return $this->render('batchi', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Opus model.
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
     * Creates a new Opus model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Opus();

        $params = Yii::$app->request->post();
        if ($model->load($params) ) {
            $images = $model->pica;
            $model->pica = $images[0];
            $model->created_at = time();
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }


        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Opus model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $params = Yii::$app->request->post();
        if ($model->load($params) ) {
            if($model->getOldAttribute('pica')  !== $model->pica){
                $model->pica = $model->pica[0];
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Opus model.
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
     * Finds the Opus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Opus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Opus::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionBatch(){
        $model = new Opus();
        $params = Yii::$app->request->post();
        if ($model->load($params) ) {
            $images = $model->pica;
            for ($i = 1; $i < count($images); $i++) {
                $new_opus = new Opus();
                $new_opus->event_id = $model->event_id;
                $new_opus->type_id = $model->type_id;
                $new_opus->name = $model->name . '_' . $i;
                $new_opus->author = $model->author . '_' . $i;
                $new_opus->summary = $model->summary . '_' . $i;
                $new_opus->content = $model->content;
                $new_opus->pica = $images[$i - 1];
                $new_opus->created_at = time();
                $new_opus->save();
            }
            $model->pica = $images[$i - 1];
            $model->created_at = time();
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        }
        Yii::info($model->getErrors());
        return $this->render('batch', [
            'model' => $model,
        ]);
    }
}
