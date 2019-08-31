<?php

use yii\helpers\Html;
use izyue\admin\widgets\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Logs');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="log-index wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
        <header class="panel-heading">
            <?= Html::encode($this->title) ?>
        </header>
        <div class="panel-body">
            <div class="adv-table editable-table ">
                <div class="clearfix">
                    <div class="btn-group">
                    </div>
                </div>

                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
//                        'user_id',
                        [
                            'attribute'=> 'opus_id',
                            'value' => 'opus.name'
                        ],
                        [
                            'attribute'=> 'event_id',
                            'value' => 'event.title'
                        ],
                        [
                            'attribute'=> 'ips',
                            'value' => function($model){
                                return long2ip($model->ips);
                            }
                        ],
                        //'created_at',
                        //'lasted_at',
                        //'remarka',
                        //'remarkb',

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
        </div>
    </section>
    <!-- page end-->
</section>
