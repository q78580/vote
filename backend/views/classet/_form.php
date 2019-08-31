<?php

use yii\helpers\Html;
use izyue\admin\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Classet */
/* @var $form backend\widgets\ActiveForm */
$events = \common\models\Events::getEvents();
?>

<div class="classet-form row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <?= Html::encode($this->title) ?>
            </header>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'event_id')->dropDownList($events) ?>

                    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'info')->textarea(['rows' => 6]) ?>

<!--                    --><?//= $form->field($model, 'created_at')->textInput() ?>
<!---->
<!--                    --><?//= $form->field($model, 'updated_at')->textInput() ?>


                    <div class="form-group col-lg-offset-2 col-lg-10">
                        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                <?php ActiveForm::end(); ?>
            </div>
        </section>
    </div>
</div>
