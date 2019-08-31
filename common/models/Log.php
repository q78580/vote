<?php
namespace common\models;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/26 0026
 * Time: 下午 1:29
 */
class Log extends _Log{
    public function rules()
    {
        $rules = parent::rules();
        return $rules;
    }

    public function getEvent(){
        return $this->hasOne(Events::className(),['id' => 'event_id']);
    }

    public function getOpus(){
        return $this->hasOne(Opus::className(),['id' => 'opus_id']);
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
}