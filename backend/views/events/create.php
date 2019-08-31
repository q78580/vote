<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Events */

$this->title = Yii::t('app', '创建活动');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="events-create wrapper site-min-height">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
