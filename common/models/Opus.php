<?php
namespace common\models;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/26 0026
 * Time: 下午 1:29
 */
class Opus extends _Opus{

    public function rules()
    {
        $rules = parent::rules();
        $rules = $this->deleteRule($rules,'required','pica');
        $rules[] = ['pica', 'required', 'message' => '新建商品头图必须设置', 'on' => ['create']];
        $rules[] = ['author', 'required', 'message' => '需设置作者'];

        return $rules;
    }

    public function fields()
    {
        $fields = parent::fields();

        unset($fields['picb'], $fields['picc'], $fields['picd']);
        unset($fields['view_num'], $fields['user_id']);
        $fields['voting_num'] = function ($model) {
            return $model->voting_num + $model->fake_voting_num;
        };
        $fields['fake_voting_num'] = function ($model) {
            return 0;
        };
        return $fields;
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

    public function getCategoryList($id){
        $model = Classet::find()->where(['event_id'=>$id])->all();
        return ArrayHelper::map($model, 'id', 'name');
    }

}