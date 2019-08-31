<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OpusSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="opus-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'event_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'author') ?>

    <?php // echo $form->field($model, 'phone') ?>

    <?php // echo $form->field($model, 'summary') ?>

    <?php // echo $form->field($model, 'pica') ?>

    <?php // echo $form->field($model, 'picb') ?>

    <?php // echo $form->field($model, 'picc') ?>

    <?php // echo $form->field($model, 'picd') ?>

    <?php // echo $form->field($model, 'piec') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'is_check') ?>

    <?php // echo $form->field($model, 'voting_num') ?>

    <?php // echo $form->field($model, 'view_num') ?>

    <?php // echo $form->field($model, 'is_display') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
