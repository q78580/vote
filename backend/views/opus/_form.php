<?php

use yii\helpers\Html;
use izyue\admin\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Opus */
/* @var $form backend\widgets\ActiveForm */
?>

<div class="opus-form row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <?= Html::encode($this->title) ?>
            </header>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'event_id')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\Events::find()->all(),'id','title'),
                    [
                        'prompt'=>'请选择活动',
                        'onchange'=>'
                            $(".form-group.field-member-area").hide();
                            $.post("'.Yii::$app->urlManager->createUrl('classet/list').'&id="+$(this).val(),function(data){
                                     $("select#opus-type_id").html(data);
                            });',
                    ]) ?>
                <?= $form->field($model, 'type_id')->dropDownList($model->getCategoryList($model->event_id))
                ?>

                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'summary')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'pica')->widget('common\widgets\multi\QiniuFileInput',[
                        'uploadUrl' => 'http://up-z0.qiniup.com',
                        'qlConfig' => [
                            'accessKey' => '七牛云ak',
                            'secretKey' => '七牛云sk',
                            'scope'=>'七牛云bucket',
                            'cdnUrl' => '七牛云cdn',//外链域名
                        ],
                        'clientOptions' => [
                            'max' => 1,//最多允许上传图片个数  默认为3
                            'size' => 10485760,//每张图片大小
                            //'btnName' => 'upload',//上传按钮名字
                            //'accept' => 'image/jpeg,image/gif,image/png'//上传允许类型
                        ],
                    ])  ?>

                    <?= $form->field($model, 'content')->widget(\yii\redactor\widgets\Redactor::className(),[
                        'clientOptions' => [
                            'lang' => 'zh_cn',
                            'minHeight' =>500,
                            'plugins' => ['fullscreen']
                        ]]) ?>


                    <?= $form->field($model, 'is_check')->dropDownList(['1'=> '已审核','0'=>'未审核']) ?>

                    <?= $form->field($model, 'voting_num')->textInput(['readonly' => 'readonly']) ?>

                    <?= $form->field($model, 'fake_voting_num')->textInput() ?>

                    <?= $form->field($model, 'is_display')->dropDownList(['1'=> '显示','0'=>'不显示']) ?>

<!--                    --><?//= $form->field($model, 'created_at')->textInput() ?>

                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-danger']) ?>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </section>
    </div>
</div>
