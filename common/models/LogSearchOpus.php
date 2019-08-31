<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Opus;

/**
 * LogSearchOpus represents the model behind the search form of `common\models\Opus`.
 */
class LogSearchOpus extends Opus
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'event_id', 'user_id', 'is_check', 'voting_num', 'view_num', 'is_display', 'created_at'], 'integer'],
            [['name', 'author', 'phone', 'summary', 'pica', 'picb', 'picc', 'picd', 'piec', 'content'], 'safe'],
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
        $query = Opus::find();

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
            'event_id' => $this->event_id,
            'user_id' => $this->user_id,
            'is_check' => $this->is_check,
            'voting_num' => $this->voting_num,
            'view_num' => $this->view_num,
            'is_display' => $this->is_display,
            'created_at' => $this->created_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'summary', $this->summary])
            ->andFilterWhere(['like', 'pica', $this->pica])
            ->andFilterWhere(['like', 'picb', $this->picb])
            ->andFilterWhere(['like', 'picc', $this->picc])
            ->andFilterWhere(['like', 'picd', $this->picd])
            ->andFilterWhere(['like', 'piec', $this->piec])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
