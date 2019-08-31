<?php

use yii\helpers\Html;
use yii\web\JsExpression;
use daixianceng\echarts\ECharts;

/* @var $this yii\web\View */
/* @var $searchModel common\models\ProfileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Profiles');
$this->params['breadcrumbs'][] = $this->title;

?>
<section class="profile-index wrapper site-min-height">
    <!-- page start-->
    <section class="panel">
        <header class="panel-heading">
            <?= Html::encode($this->title) ?>
        </header>
        <div class="panel-body">
            <div class="adv-table editable-table ">

                <?= \izyue\admin\widgets\GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        [
                            'attribute' => 'id',
                            'options' => ['width' => 50]
                        ],
                        [
                            'attribute' => 'avatar',
                            'format' => ['image',['width' => '30','height' => '30']],
                            'options' => ['width' => 100]
                        ],
                        [
                            'attribute' => 'nickname',
                            'value' => function($model){
                                if($model->jsnick == null  && $model->nickname){
                                    return '未授权';
                                }
                                if($model->jsnick == null){
                                    return $model->nickname;
                                }
                                return json_decode($model->jsnick);
                            },
                            'options' => ['width' => 100]
                        ],
                        [
                            'attribute' => 'city',
                            'options' => ['width' => 200]
                        ],
                        [
                            'attribute' => 'created_at',
                            'format'=> 'datetime',
                            'filter'=>Html::dropDownList("ProfileSearch[created_at]",$searchModel->created_at,[1=>'未授权',2=>'已授权'],['prompt' => Yii::t('app', '全部')]),
                            'options' => ['width' => 200]
                        ],
//                        [
//                            'class' => 'yii\grid\ActionColumn',
//                            'header' => '操作',
//                            'headerOptions'=>['width'=>250],
//                            'template' => '{look}&nbsp;&nbsp;&nbsp;{forecast}',
//                            'buttons' => [
//                                'look'=> function($url,$model,$key){
//                                    return Html::a('查看孩子',\yii\helpers\Url::to(['person/index','user_id'=>$model->id,'sort'=>'-id','PersonSearch[user_id]'=>$model->id]));
//                                },
//                                'forecast'=> function($url,$model,$key){
//                                    return Html::a('预测身高',\yii\helpers\Url::to(['predict/index','sort'=>'-id','PredictSearch[user_id]'=>$model->id]));
//                                },
//                            ],
//                        ],
                        ['class' => 'yii\grid\ActionColumn', 'template' => '{view}'],
                    ],
                ]); ?>
            </div>
        </div>
    </section>
    <!-- page end-->
</section>
<?//= ECharts::widget([
//    'theme' => 'dark',
//    'responsive' => true,
//    'options' => [
//        'style' => 'height: 400px;'
//    ],
//    'pluginEvents' => [
//        'click' => [
//            new JsExpression('function (params) {console.log(params)}'),
//            new JsExpression('function (params) {console.log("ok")}')
//        ],
//        'legendselectchanged' => new JsExpression('function (params) {console.log(params.selected)}')
//    ],
//    'pluginOptions' => [
//        'option' => [
//            'title' => [
//                'text' => '折线图堆叠'
//            ],
//            'tooltip' => [
//                'trigger' => 'axis'
//            ],
//            'legend' => [
//                'data' => ['邮件营销', '联盟广告', '视频广告', '直接访问', '搜索引擎']
//            ],
//            'grid' => [
//                'left' => '3%',
//                'right' => '4%',
//                'bottom' => '3%',
//                'containLabel' => true
//            ],
//            'toolbox' => [
//                'feature' => [
//                    'saveAsImage' => []
//                ]
//            ],
//            'xAxis' => [
//                'name' => '日期',
//                'type' => 'category',
//                'boundaryGap' => false,
//                'data' => ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
//            ],
//            'yAxis' => [
//                'type' => 'value'
//            ],
//            'series' => [
//                [
//                    'name' => '邮件营销',
//                    'type' => 'line',
//                    'stack' => '总量',
//                    'data' => [120, 132, 101, 134, 90, 230, 210]
//                ],
//                [
//                    'name' => '联盟广告',
//                    'type' => 'line',
//                    'stack' => '总量',
//                    'data' => [220, 182, 191, 234, 290, 330, 310]
//                ],
//                [
//                    'name' => '视频广告',
//                    'type' => 'line',
//                    'stack' => '总量',
//                    'data' => [150, 232, 201, 154, 190, 330, 410]
//                ],
//                [
//                    'name' => '直接访问',
//                    'type' => 'line',
//                    'stack' => '总量',
//                    'data' => [320, 332, 301, 334, 390, 330, 320]
//                ],
//                [
//                    'name' => '搜索引擎',
//                    'type' => 'line',
//                    'stack' => '总量',
//                    'data' => [820, 932, 901, 934, 1290, 1330, 1320]
//                ]
//            ]
//        ]
//    ]
//]); ?>
