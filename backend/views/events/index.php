<?php

use yii\helpers\Html;
use izyue\admin\widgets\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\EventsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Events');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="events-index wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
        <header class="panel-heading">
            <?= Html::encode($this->title) ?>
        </header>
        <div class="panel-body">
            <div class="adv-table editable-table ">
                <div class="clearfix">
                    <div class="btn-group">
                        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
                            'modelClass' => Yii::t('app','活动')]), ['create'], ['class' => 'btn btn-success', 'style' => 'margin-bottom:15px;']) ?>
                    </div>
                </div>

                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        [
                            'attribute' => 'status',
//                            'filter'=>Html::dropDownList("StoreSearch[status]",$searchModel->status,$searchModel->status_zh,['prompt' => Yii::t('app', 'Please Filter')]),
                            'value' => function($model,$key,$index,$column) {
                                return $model->status_zh[$model->status];
                            }
                        ],
                        'title',
//                        'pic',
                        //'icon',
                        //'content:ntext',
                        //'user_max',
                        //'ip_max',
                        //'fake_view_num',
                        //'fake_voting_num',
                        //'real_view_num',
                        //'real_voting_num',
                        //'need_cheek',
                        //'report_time:datetime',
                        //'report_deadline',
                        'voting_time:datetime',
                        'voting_deadline:datetime',
                        'created_at:datetime',
                        //'updated_at',

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
        </div>
    </section>
    <!-- page end-->
</section>
