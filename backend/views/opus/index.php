<?php

use yii\helpers\Html;
use izyue\admin\widgets\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LogSearchOpus */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Opuses');
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="opus-index wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
        <header class="panel-heading">
            <?= Html::encode($this->title) ?>
        </header>
        <div class="panel-body">
            <div class="adv-table editable-table ">
                <div class="clearfix">
                    <div class="btn-group">
                        <?= Html::a(Yii::t('app', 'Create Opus'), ['create'], ['class' => 'btn btn-success', 'style' => 'margin-bottom:15px;']) ?>
                     </div>
                    <div class="btn-group">
                       <?= Html::a(Yii::t('app', '批量新建'), ['batch'], ['class' => 'btn btn-success', 'style' => 'margin-bottom:15px;']) ?>
                    </div>
                </div>

                <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'id',
                        'event_id',
                        'user_id',
                        'name',
                        'author',
                        //'phone',
                        //'summary',
                        //'pica',
                        //'picb',
                        //'picc',
                        //'picd',
                        //'piec',
                        //'content:ntext',
                        //'is_check',
                        //'voting_num',
                        //'view_num',
                        //'is_display',
                        //'created_at',

                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
            </div>
        </div>
    </section>
    <!-- page end-->
</section>
