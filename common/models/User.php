<?php
namespace common\models;

use Yii;
use common\models\_User;

class User extends _User
{
    public function rules()
    {
        return yii\helpers\ArrayHelper::merge(parent::rules(), [
            // ['access_token', 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return yii\helpers\ArrayHelper::merge(parent::attributeLabels(), [
            // 'access_token' => '访问token',
        ]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['password_hash' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        if($insert){
            $profile = new Profile();
            $profile->id = $this->id;
            $profile->status = 0;
            $profile->save(false);
        }else{
            $profile = Profile::findOne($this->id);
            if(!$profile){
                $profile = new Profile();
                $profile->id = $this->id;
                $profile->status = 0;
                $profile->save(false);
            }
        }
        return parent::afterSave($insert, $changedAttributes);
    }
}
