<?php

use yii\helpers\Html;
use izyue\admin\widgets\ActiveForm;
use common\models\Events;
//['placeholder' => "."]
/* @var $this yii\web\View */
/* @var $model common\models\Events */
/* @var $form backend\widgets\ActiveForm */
?>

<div class="events-form row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <?= Html::encode($this->title) ?>
            </header>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'pic')->widget('common\widgets\multi\QiniuFileInput',[
                        'uploadUrl' => 'http://up-z0.qiniup.com',
                        'qlConfig' => [
                            'accessKey' => '5A71qjhz__OYE3O_JNwpRfMjGx9zJy7rBvTorrly',
                            'secretKey' => 'Q53_-HDnso3l1IElIU4n-or6MJa_WccnYNwa_shO',
                            'scope'=>'huayi',
                            'cdnUrl' => 'http://p9mpndptx.bkt.clouddn.com/',//外链域名
                        ],
                        'options' => ['placeholder' => 'Select a Parent Account...'],
                        'clientOptions' => [
                            'max' => 1,//最多允许上传图片个数  默认为3
                            'size' => 10485760,//每张图片大小
                            //'btnName' => 'upload',//上传按钮名字
                            //'accept' => 'image/jpeg,image/gif,image/png'//上传允许类型
                        ],
                    ])  ?>

                    <?= $form->field($model, 'icon')->widget('common\widgets\multi\QiniuFileInput',[
                        'uploadUrl' => 'http://up-z0.qiniup.com',
                        'qlConfig' => [
                            'accessKey' => '5A71qjhz__OYE3O_JNwpRfMjGx9zJy7rBvTorrly',
                            'secretKey' => 'Q53_-HDnso3l1IElIU4n-or6MJa_WccnYNwa_shO',
                            'scope'=>'huayi',
                            'cdnUrl' => 'http://p9mpndptx.bkt.clouddn.com/',//外链域名
                        ],
                        'clientOptions' => [
                            'max' => 1,//最多允许上传图片个数  默认为3
                            'size' => 10485760,//每张图片大小
                            //'btnName' => 'upload',//上传按钮名字
                            //'accept' => 'image/jpeg,image/gif,image/png'//上传允许类型
                        ],
                    ])  ?>

                    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'user_max')->textInput() ?>

                    <?= $form->field($model, 'ip_max')->textInput() ?>

                    <?= $form->field($model, 'fake_voting_num')->textInput() ?>

                    <?= $form->field($model, 'real_voting_num')->textInput() ?>

                    <?= $form->field($model, 'voting_time')->widget('trntv\yii\datetimepicker\DatetimepickerWidget',['clientOptions'=>['locale'=>'en']])?>

                    <?= $form->field($model, 'voting_deadline')->widget('trntv\yii\datetimepicker\DatetimepickerWidget',['clientOptions'=>['locale'=>'en']]) ?>
                    <?php
                        if($model->isNewRecord){
                        }else{
                            echo $form->field($model, 'status')->dropDownList(['0'=>"下架",'1'=>'正常']);
                        }
                    ?>
                    <div class="form-group">
                        <div class="col-lg-offset-2 col-lg-10">
                            <?php
                            if($model->isNewRecord){
                                if(Events::isAlive()){
                                    echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-danger','data' => [ 'confirm' => Yii::t('app', '当前有在线的活动确认创建下架的活动?'), 'method' => 'post']]);
                                }else{
                                    echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-danger','data' => [ 'confirm' => Yii::t('app', '确认提交?'), 'method' => 'post']]);
                                }
                            }else{
                                echo Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-danger','data' => [ 'confirm' => Yii::t('app', '确认提交?'), 'method' => 'post']]);
                            }

                            ?>
                        </div>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </section>
    </div>
</div>
