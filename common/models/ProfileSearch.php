<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Profile;

/**
 * ProfileSearch represents the model behind the search form of `common\models\Profile`.
 */
class ProfileSearch extends Profile
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'sex', 'status'], 'integer'],
            [['nickname', 'jsnick','username', 'city', 'address', 'phone','created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Profile::find()->from(['profile' => self::tableName()]);
//            ->leftJoin(['p' => Person::find()->select(['user_id','count(1) as nums'])->where(['person.status'=>1])->groupBy(['user_id'])], 'p.user_id = profile.id');
//        $query->select(['profile.*','p.nums']);;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
//        if(isset($this->created_at)){
//            if($this->created_at == 2){
//                $query->andWhere(['>','created_at',$this->created_at]);
//            }else if($this->created_at == 1){
//                $query->andWhere(['created_at'=>null]);
//            }
//        }
//        Yii::info($this->nums);
//        if(isset($this->nums)){
//            if($this->nums == 2){
//                $query->andWhere(['>','p.nums',0]);
//            }else if($this->nums == 1){
//                $query->andWhere(['p.nums'=>null]);
//            }
//        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'sex' => $this->sex,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'nickname', $this->nickname])
            ->andFilterWhere(['like', 'jsnick', $this->jsnick])
//            ->andFilterWhere(['like', 'avatar', $this->avatar])
            ->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
}
