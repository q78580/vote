<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property int $user_id
 * @property int $opus_id
 * @property int $event_id
 * @property string $ips
 * @property int $created_at
 * @property int $lasted_at
 * @property int $remarka
 * @property string $remarkb
 */
class _Log extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'opus_id', 'event_id', 'ips', 'created_at'], 'required'],
            [['user_id', 'opus_id', 'event_id', 'created_at', 'lasted_at', 'remarka'], 'integer'],
            [['ips'], 'string', 'max' => 100],
            [['remarkb'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', '用户'),
            'opus_id' => Yii::t('app', '作品名称'),
            'event_id' => Yii::t('app', '活动名称'),
            'ips' => Yii::t('app', 'IP'),
            'created_at' => Yii::t('app', 'Created At'),
            'lasted_at' => Yii::t('app', 'Lasted At'),
            'remarka' => Yii::t('app', 'Remarka'),
            'remarkb' => Yii::t('app', 'Remarkb'),
        ];
    }
}
