<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Log;

/**
 * LogSearch represents the model behind the search form of `common\models\Log`.
 */
class LogSearch extends Log
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'opus_id', 'event_id', 'created_at', 'lasted_at', 'remarka'], 'integer'],
            [['ips', 'remarkb'], 'safe'],
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
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Log::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'opus_id' => $this->opus_id,
            'event_id' => $this->event_id,
            'created_at' => $this->created_at,
            'lasted_at' => $this->lasted_at,
            'remarka' => $this->remarka,
        ]);

        $query->andFilterWhere(['like', 'ips', $this->ips])
            ->andFilterWhere(['like', 'remarkb', $this->remarkb]);

        return $dataProvider;
    }
}
