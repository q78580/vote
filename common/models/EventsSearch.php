<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Events;

/**
 * EventsSearch represents the model behind the search form of `common\models\Events`.
 */
class EventsSearch extends Events
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'status', 'user_max', 'ip_max', 'fake_view_num', 'fake_voting_num', 'real_view_num', 'real_voting_num', 'need_cheek', 'report_time', 'report_deadline', 'voting_time', 'voting_deadline', 'created_at', 'updated_at'], 'integer'],
            [['title', 'pic', 'icon', 'scan', 'content'], 'safe'],
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
        $query = Events::find();

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
            'status' => $this->status,
            'user_max' => $this->user_max,
            'ip_max' => $this->ip_max,
            'fake_view_num' => $this->fake_view_num,
            'fake_voting_num' => $this->fake_voting_num,
            'real_view_num' => $this->real_view_num,
            'real_voting_num' => $this->real_voting_num,
            'need_cheek' => $this->need_cheek,
            'report_time' => $this->report_time,
            'report_deadline' => $this->report_deadline,
            'voting_time' => $this->voting_time,
            'voting_deadline' => $this->voting_deadline,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'pic', $this->pic])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'scan', $this->scan])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
