<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/26 0026
 * Time: 下午 1:29
 */
class Classet extends _Class{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
            ],
        ];
    }
}