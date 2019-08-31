<?php
namespace common\models;
use yii\behaviors\TimestampBehavior;
use Yii;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/26 0026
 * Time: 下午 1:29
 */
class Events extends _Events{

    public $status_zh = [1=>'正常',0=>'下架'];
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

    public function rules()
    {
        $rules = parent::rules();
        $rules = $this->deleteRule($rules,'integer','voting_time');
        $rules = $this->deleteRule($rules,'integer','voting_deadline');
        $rules = $this->deleteRule($rules,'required','status');
        $rules[] = [['pic','icon'], 'required', 'message' => '需设置封面图片','on'=>['create']];
        $rules[] = [['voting_time','voting_deadline'], 'filter', 'filter'=>'strtotime'];
        $rules[] = [['ip_max','user_max'], 'default', 'value'=>0];
        $rules[] = [['status'],'default','value'=>function($model){
            if($this::isAlive()){
                return 0;
            }
            return 1;
        }];
        $rules[] = ['voting_deadline',function($attribute, $params){
            if($this->getOldAttribute('status') == 0 && $this->status == 1){
                if($this->voting_deadline < time()){
                    $this->addError($attribute,'若将活动设为正常，请注意投票截止日期设置');
                }
            }

        }];

        return $rules;
    }

    private function deleteRule($rules,$type,$attribute) {
        foreach($rules as $key => $rule) {
            if($rule[1] == $type){
                if(is_array($rule[0])){
                    foreach($rule[0] as $k=>$r){
                        if($r == $attribute)
                            unset($rules[$key][0][$k]);
                    }
                }
                else
                    unset($rule[0]);
            }
        }

        return $rules;
    }

    public static function isAlive(){

        if(Events::find()->where(['status'=>1])->exists()){
            return true;
        }
        return false;
    }

    public static function getEvents()
    {
        $events = [];
        $result = self::find()->asArray()->all();
        if($result){
            foreach ($result as $k => $v){
                $events[$v['id']] = $v['title'];
            }
        }
        return $events;
    }

    public function hasOpus($id){
        if(Opus::find()->where(['event_id'=>$id])->exists()){
            return true;
        }
        return false;
    }
}