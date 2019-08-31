<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Log */

$this->title = Yii::t('app', 'Create Log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-create wrapper site-min-height">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
