<?php

use yii\helpers\Html;
use izyue\admin\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Profile */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Profiles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="profile-view wrapper site-min-height">
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    <?= Html::encode($this->title) ?>
                </header>
                <div class="panel-body">

                    <div class="row">
                        <div class="col-lg-11">
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [

                                    'id',
                                    'nickname',
                                    'jsnick',
                                    'avatar',
                                    'username',
                                    'sex',
                                    'city',
                                    'address',
                                    'phone',
                                    'status',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>
