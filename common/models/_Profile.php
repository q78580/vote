<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "profile".
 *
 * @property int $id
 * @property string $nickname 昵称
 * @property string $jsnick
 * @property string $avatar 头像
 * @property string $username 姓名
 * @property int $sex 性别
 * @property string $city 城市
 * @property string $address 邮寄地址
 * @property string $phone 手机号
 * @property int $status 账号状态
 * @property int $created_at 授权时间
 */
class _Profile extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'sex', 'status', 'created_at'], 'integer'],
            [['nickname', 'jsnick', 'avatar', 'username', 'city', 'address'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
            [['id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'nickname' => Yii::t('app', '昵称'),
            'jsnick' => Yii::t('app', '全昵称'),
            'avatar' => Yii::t('app', '头像'),
            'username' => Yii::t('app', '姓名'),
            'sex' => Yii::t('app', '性别'),
            'city' => Yii::t('app', '城市'),
            'address' => Yii::t('app', '邮寄地址'),
            'phone' => Yii::t('app', '手机号'),
            'status' => Yii::t('app', '账号状态'),
            'created_at' => Yii::t('app', '授权时间'),
        ];
    }
}
