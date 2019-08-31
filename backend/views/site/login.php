<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('common', 'login');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'options'=>[
        'class'=>'form-signin lock-box text-center'
    ]
]); ?>

<a class="logo floatless" >投票<span>系统</span>后台</a>
<div class="login-wrap">

    <?= $form->field($model, 'username',[
        'inputOptions' => ['class'=>'form-control', 'placeholder' => Yii::t('common', 'username')],
        'inputTemplate' => '<div class="input-group m-bot15">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                {input}
                            </div>',
    ])->textInput(['placeholder' => $model->getAttributeLabel('username')])->label(false) ?>
    <?= $form->field($model, 'password',[
        'inputOptions' => ['class'=>'form-control', 'placeholder' => Yii::t('common', 'password')],
        'inputTemplate' => '<div class="input-group m-bot15">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                {input}
                            </div>',
    ])->passwordInput(['placeholder' => $model->getAttributeLabel('password')])->label(false) ?>

    <?= $form->field($model, 'rememberMe',[
        'inputTemplate'=>'{input}',
        'options' => [
        ],
    ])->checkbox() ?>

    <?= Html::submitButton(Yii::t('common', 'login'), ['class' => 'btn btn-lg btn-login btn-block', 'name' => 'login-button']) ?>
</div>
<?php ActiveForm::end(); ?>

