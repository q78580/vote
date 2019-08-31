<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Log */

$this->title = Yii::t('app', '更新');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<section class="log-update wrapper site-min-height">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</section>
