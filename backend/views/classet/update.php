<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Classet */

$this->title = Yii::t('app', '更新作品类型');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Classets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<section class="classet-update wrapper site-min-height">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</section>
