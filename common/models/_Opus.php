<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "opus".
 *
 * @property int $id
 * @property int $event_id
 * @property int $user_id
 * @property int $type_id
 * @property string $name
 * @property string $author
 * @property string $phone
 * @property string $summary
 * @property string $pica
 * @property string $picb
 * @property string $picc
 * @property string $picd
 * @property string $pice
 * @property string $content
 * @property int $is_check
 * @property int $voting_num
 * @property int $fake_voting_num
 * @property int $view_num
 * @property int $is_display
 * @property int $created_at
 *
 * @property Events $event
 * @property Classet $classet
 */
class _Opus extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'opus';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event_id', 'type_id', 'name', 'pica'], 'required'],
            [['event_id', 'user_id', 'type_id', 'is_check', 'voting_num', 'fake_voting_num', 'view_num', 'is_display', 'created_at'], 'integer'],
            [['content'], 'string'],
            [['name', 'author', 'summary', 'pica', 'picb', 'picc', 'picd', 'pice'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
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
            'user_id' => Yii::t('app', 'User ID'),
            'type_id' => Yii::t('app', '类型'),
            'name' => Yii::t('app', '标题'),
            'author' => Yii::t('app', '作者'),
            'phone' => Yii::t('app', '联系方式'),
            'summary' => Yii::t('app', '简介'),
            'pica' => Yii::t('app', '作品'),
            'picb' => Yii::t('app', 'Picb'),
            'picc' => Yii::t('app', 'Picc'),
            'picd' => Yii::t('app', 'Picd'),
            'pice' => Yii::t('app', 'Pice'),
            'content' => Yii::t('app', '内容'),
            'is_check' => Yii::t('app', '审核状态'),
            'voting_num' => Yii::t('app', '投票数量'),
            'fake_voting_num' => Yii::t('app', '虚拟投票量'),
            'view_num' => Yii::t('app', '点击数量'),
            'is_display' => Yii::t('app', '显示状态'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvent()
    {
        return $this->hasOne(Events::className(), ['id' => 'event_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClasset()
    {
        return $this->hasOne(Classet::className(), ['id' => 'type_id']);
    }
}
