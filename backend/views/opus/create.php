<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Opus */

$this->title = Yii::t('app', '创建作品');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Opuses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="opus-create wrapper site-min-height">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
