<?php

use yii\helpers\Html;
//use izyue\admin\widgets\GridView;
use kartik\grid\GridView;
use kartik\editable\Editable;

/* @var $this yii\web\View */
/* @var $searchModel common\models\LogSearchOpus */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', '作品');
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
                    </div>

                    <?= GridView::widget([
                        'panel' => [
                            'heading'=>false,//不要了
                            'before'=>'<div style="margin-top:8px">{summary}</div>',//放在before中，前面的div主要是想让它好看
                        ],
                        'toolbar' =>  [
                            ['content' =>
                                Html::a('新建作品', ['create'], ['class' => 'btn btn-success', 'style' => 'margin-bottom:15px;']).' '.
                                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['batch'],['type' => 'button', 'title' => Yii::t('kvgrid', '批量创建'), 'class' => 'btn btn-success', 'onclick' => 'alert("即将跳转批量创建表单页面!");']) . ' '.
                                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('kvgrid', 'Reset Grid')])
                            ],
                            '{export}',
//                            '{toggleData}',
                        ],
                        'export' => [
                            'fontAwesome' => true
                        ],

                        'exportConfig' => [
                            GridView::CSV => [
                                'label' => '导出CSV',
                                'iconOptions' => ['class' => 'text-primary'],
//                                'showHeader' => true,
//                                'showPageSummary' => true,
//                                'showFooter' => true,
//                                'showCaption' => true,
                                'filename' => '用户表'.date("Y-m-d"),
                                'alertMsg' => '确定要导出CSV格式文件？',
                                'options' => [
                                    'title'=>'',
                                ],
                                'mime' => 'application/csv',
                                'config' => [
                                    'colDelimiter' => ",",
                                    'rowDelimiter' => "\r\n",
                                ],
                            ],
                        ],

                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'responsive'=>true,//自适应，默认为true
                        'pager' => [
                            'options'=>['class'=>'pagination'],   // set clas name used in ui list of pagination
                            'prevPageLabel' => '上一页',   // Set the label for the "previous" page button
                            'nextPageLabel' => '下一页',   // Set the label for the "next" page button
                            'firstPageLabel'=>'首页',   // Set the label for the "first" page button
                            'lastPageLabel'=>'尾页',    // Set the label for the "last" page button
                            'nextPageCssClass'=>'next',    // Set CSS class for the "next" page button
                            'prevPageCssClass'=>'prev',    // Set CSS class for the "previous" page button
                            'firstPageCssClass'=>'first',    // Set CSS class for the "first" page button
                            'lastPageCssClass'=>'last',    // Set CSS class for the "last" page button
                            'maxButtonCount'=>10,    // Set maximum number of page buttons that can be displayed
                        ],
//                        'hover'=>true,//鼠标移动上去时，颜色变色，默认为false
                        'floatHeader'=>true,//向下滚动时，标题栏可以fixed，默认为false
                        'containerOptions' => ['style' => 'overflow: auto'], // only set when $responsive = false
                        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
//                        'toggleDataOptions'=>[
//                            'maxCount' => 200,//当超过200条时，此按钮隐藏，以免数据太多造成加载问题
//                            'minCount' => 10,//当现有总条数大于此值时,点击不会出现下方提示
//                            'confirmMsg' => '总共'. number_format($dataProvider->getTotalCount()).'条数据，确定要显示全部？',//点击时的确认
//                        ],
                        'pjax' => false, // pjax is set to always true for this demo
                        'columns' => [
                            [
                                'class' => '\kartik\grid\CheckboxColumn',
                                'rowSelectedClass' => GridView::TYPE_INFO,
                                'visible'=>true,//不显示，代码也没有
                                'hidden'=>false,//隐藏，代码还有，导出csv等时还存在
                                'hiddenFromExport'=>false,//虽然显示，但导出csv时忽略掉
                                'pageSummary'=>'总计',//可以是字符串，当为true时，自动合计
                                'mergeHeader'=>true,//合并标题和检索栏
                            ],
                            [
                                'attribute'=>'id',
                                'options'=>['width'=>'50']
                            ],

                            [
                                'attribute'=>'event.title',
                                'label'=>'所属活动',
                                 'options'=>['width'=>'100']
                            ],
                            [
                                'attribute'=>'name',
                                'class'=>'kartik\grid\EditableColumn',
                                'editableOptions'=>[
                                    'asPopover' => false,
                                    'inputType'=>\kartik\editable\Editable::INPUT_TEXTAREA,
                                ],
                                'label'=>'标题'
                            ],
                            [
                                'attribute' => 'pica',
                                'value' => function ($model) {
                                    return $model->pica.'?imageslim';
                                },
                                'format' => ['image',['width' => '30','height' => '30']],
                                'options' => ['width' => 100],
                                'label'=>'作品缩略图',
                            ],
//                            [
//                                'attribute'=>'summary',
//                                'class'=>'kartik\grid\EditableColumn',
//                                'editableOptions'=>[
//                                    'asPopover' => false,
//                                    'inputType'=>\kartik\editable\Editable::INPUT_TEXTAREA,
//                                ],
//                                'label'=>'简介'
//                            ],
                            [
                                'attribute'=>'author',
                                'class'=>'kartik\grid\EditableColumn',
                                'editableOptions'=>[
                                    'asPopover' => false,
                                    'inputType'=>\kartik\editable\Editable::INPUT_TEXTAREA,
                                ],
                                'label'=>'作者',
                                'options'=>['width'=>'70']
                            ],
//                            [
//                                'attribute'=>'phone',
//                                'class'=>'kartik\grid\EditableColumn',
//                                'editableOptions'=>[
//                                    'asPopover' => false,
//                                    'inputType'=>\kartik\editable\Editable::INPUT_TEXTAREA,
//                                ],
//                                'label'=>'手机号',
//                                'options'=>['width'=>'130']
//                            ],
//                            [
//                                'attribute'=>'is_check',
//                                'class'=>'kartik\grid\EditableColumn',
//                                'editableOptions'=>[
//                                    'asPopover' => false,
//                                    'inputType'=>\kartik\editable\Editable::INPUT_DROPDOWN_LIST,
//                                    'data' => [0=>'未审核',1=>'已审核'],
//                                ],
//                                'value' => function ($model) {
//                                    return $model->is_check === 0 ?'未审核':'已审核';
//                                },
//                                'filter' =>['未审核','已审核'],
//                                'label'=>'审核状态',
//                                'options'=>['width'=>'80']
//                            ],
                            [
                                'attribute' => 'fake_voting_num',
                                'class'=>'kartik\grid\EditableColumn',
                                'editableOptions'=>[
                                    'inputType'=>\kartik\editable\Editable::INPUT_TEXTAREA,
                                    'asPopover' => false,
                                ],
                                'label'=>'虚拟投票数',
                                'options'=>['width'=>'90']
                            ],
                            [
                                'attribute' => 'voting_num',
//                                'class'=>'kartik\grid\EditableColumn',
//                                'editableOptions'=>[
//                                    'inputType'=>\kartik\editable\Editable::INPUT_TEXTAREA,
//                                    'asPopover' => false,
//                                ],
                                'label'=>'投票数',
                                'options'=>['width'=>'80']
                            ],
                            [
                                'attribute'=>'is_display',
                                'class'=>'kartik\grid\EditableColumn',
                                'editableOptions'=>[
                                    'asPopover' => false,
                                    'inputType'=>\kartik\editable\Editable::INPUT_DROPDOWN_LIST,
                                    'data' => [0=>'不显示',1=>'显示'],
                                ],
                                'value' => function ($model) {
                                    return $model->is_display === 0 ?'不显示':'显示';
                                },
                                'filter' =>['不显示','显示'],
                                'label'=>'显示状态',
                                'options'=>['width'=>'80']
                            ],
                            [
                                'attribute'=>'created_at',
                                'format' => ['date', 'Y-m-d h:m:s'],
                                'options'=>['width'=>'150']
                            ],
                            //时间组件
//                            [
//                                'attribute' => 'updated_at',
                                // 'headerOptions' => ['width' => '150px'],
//                                'class'=>'kartik\grid\EditableColumn',
//                                'editableOptions'=>[
//                                    'inputType'=>\kartik\editable\Editable::INPUT_DATETIME,
//                                    'asPopover' => false,
//                                    'contentOptions' => ['style'=>'width:250px'],
//                                ],
//                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{details}',
                                'buttons' => [
                                    'details' => function($url,$moel,$key){
                                        return Html::a('投票明细',\yii\helpers\Url::to(['log/index','LogSearch[opus_id]'=>$key]));
                                    }
                                ],
                                'options'=>['width'=>100]
                            ],

                            ['class' => 'yii\grid\ActionColumn'],

                        ],
                    ]); ?>
                </div>
            </div>
        </section>
        <!-- page end-->
    </section>
