<?php

use yii\helpers\Html;
use izyue\admin\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Profile */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Site'), 'url' => ['index']];
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
                            <div class="clearfix">
                                <div class="btn-group">
                                    <?= Html::a(Yii::t('app', '更新测量数据'), ['reset'], ['class' => 'btn btn-danger', 'style' => 'margin-bottom:15px;','data'=>['confirm' => '危险操作，请确认是否重置测量数据?',]]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>
