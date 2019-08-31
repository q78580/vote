<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\EventsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="events-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'status') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'type_id') ?>

    <?= $form->field($model, 'pic') ?>

    <?php // echo $form->field($model, 'icon') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'user_max') ?>

    <?php // echo $form->field($model, 'ip_max') ?>

    <?php // echo $form->field($model, 'fake_view_num') ?>

    <?php // echo $form->field($model, 'fake_voting_num') ?>

    <?php // echo $form->field($model, 'real_reprot_num') ?>

    <?php // echo $form->field($model, 'real_voting_num') ?>

    <?php // echo $form->field($model, 'need_cheek') ?>

    <?php // echo $form->field($model, 'report_time') ?>

    <?php // echo $form->field($model, 'report_deadline') ?>

    <?php // echo $form->field($model, 'voting_time') ?>

    <?php // echo $form->field($model, 'voting_deadline') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
