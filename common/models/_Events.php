<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "events".
 *
 * @property int $id
 * @property int $status
 * @property string $title
 * @property string $pic
 * @property string $icon
 * @property string $scan
 * @property string $content
 * @property int $user_max
 * @property int $ip_max
 * @property int $fake_view_num
 * @property int $fake_voting_num
 * @property int $real_view_num
 * @property int $real_voting_num
 * @property int $need_cheek
 * @property int $report_time 报名开始时间
 * @property int $report_deadline 报名截止时间
 * @property int $voting_time 投票开始时间
 * @property int $voting_deadline 投票截止时间
 * @property int $created_at
 * @property int $updated_at
 */
class _Events extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','status'], 'required'],
            [['status', 'user_max', 'ip_max', 'fake_view_num', 'fake_voting_num', 'real_view_num', 'real_voting_num', 'need_cheek', 'report_time', 'report_deadline', 'voting_time', 'voting_deadline', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string'],
            [['title', 'pic', 'icon', 'scan'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', '状态'),
            'title' => Yii::t('app', '标题'),
            'pic' => Yii::t('app', '封面图基本型号'),
            'icon' => Yii::t('app', '封面图IphoneX'),
            'scan' => Yii::t('app', '二维码'),
            'content' => Yii::t('app', '内容'),
            'user_max' => Yii::t('app', '用户每天最大投票次数'),
            'ip_max' => Yii::t('app', 'IP每天最大投票次数'),
            'fake_view_num' => Yii::t('app', '虚拟点击量'),
            'fake_voting_num' => Yii::t('app', '虚拟投票数'),
            'real_view_num' => Yii::t('app', '真实点击量'),
            'real_voting_num' => Yii::t('app', '真实投票数'),
            'need_cheek' => Yii::t('app', '作品是否需要审核'),
            'report_time' => Yii::t('app', '报名开始时间'),
            'report_deadline' => Yii::t('app', '报名截止时间'),
            'voting_time' => Yii::t('app', '投票开始时间'),
            'voting_deadline' => Yii::t('app', '投票截止时间'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
