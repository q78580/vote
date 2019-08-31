<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Classet */

$this->title = Yii::t('app', '创建作品类型');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Classets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="classet-create wrapper site-min-height">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
