<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Opus */

$this->title = Yii::t('app', '更新作品');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Opuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<section class="opus-update wrapper site-min-height">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</section>
