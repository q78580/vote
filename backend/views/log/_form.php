<?php

use yii\helpers\Html;
use izyue\admin\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Log */
/* @var $form backend\widgets\ActiveForm */
?>

<div class="log-form row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <?= Html::encode($this->title) ?>
            </header>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'user_id')->textInput() ?>

                    <?= $form->field($model, 'opus_id')->textInput() ?>

                    <?= $form->field($model, 'event_id')->textInput() ?>

<!--                    --><?//= $form->field($model, 'ips')->textInput(['maxlength' => true]) ?>

<!--                    --><?//= $form->field($model, 'created_at')->textInput() ?>

<!--                    --><?//= $form->field($model, 'lasted_at')->textInput() ?>

<!--                    --><?//= $form->field($model, 'remarka')->textInput() ?>

<!--                    --><?//= $form->field($model, 'remarkb')->textInput(['maxlength' => true]) ?>

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
