<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "class".
 *
 * @property int $id
 * @property int $event_id
 * @property string $name
 * @property string $info
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Events $event
 */
class _Class extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'class';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'name'], 'required'],
            [['event_id', 'created_at', 'updated_at'], 'integer'],
            [['info'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['event_id'], 'exist', 'skipOnError' => true, 'targetClass' => Events::className(), 'targetAttribute' => ['event_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'event_id' => Yii::t('app', '活动名称'),
            'name' => Yii::t('app', '类型名称'),
            'info' => Yii::t('app', '详情'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Events::className(), ['id' => 'event_id']);
    }
}
