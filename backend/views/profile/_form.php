<?php

use yii\helpers\Html;
use izyue\admin\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Profile */

?>

<div class="profile-form row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                <?= Html::encode($this->title) ?>
            </header>
            <div class="panel-body">
                <?php $form = ActiveForm::begin(); ?>
                    <?= $form->field($model, 'id')->textInput() ?>

                    <?= $form->field($model, 'nickname')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'jsnick')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'avatar')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'sex')->textInput() ?>

                    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'status')->textInput() ?>

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
