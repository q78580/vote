<?php

namespace components;

use Yii;
use yii\data\ActiveDataProvider;
use yii\base\InvalidConfigException;
use yii\db\QueryInterface;

class CustomSortDataProvider extends ActiveDataProvider
{
    public $before_deadline_query;
    public $after_deadline_query;

    protected function prepareModels()
    {
        if (!$this->before_deadline_query instanceof QueryInterface) {
            throw new InvalidConfigException('The "query" property must be an instance of a class that implements the QueryInterface e.g. yii\db\Query or its subclasses.');
        }
        if (!$this->after_deadline_query instanceof QueryInterface) {
            throw new InvalidConfigException('The "query" property must be an instance of a class that implements the QueryInterface e.g. yii\db\Query or its subclasses.');
        }

        if (($pagination = $this->getPagination()) !== false) {
            $count_before = $this->before_deadline_query->count();
            $count_after = $this->after_deadline_query->count();
            $pagination->totalCount = $count_before + $count_after;
            $limit = $pagination->getLimit();
            $offset = $pagination->getOffset();

            if(($offset < $count_before) && ($offset+$limit <= $count_before)) {
                $query_sort = $this->before_deadline_query->limit($limit)->offset($offset);
            } else if(($offset < $count_before) && ($offset + $limit  > $count_before)){
                $query_sort = $this->before_deadline_query->limit($count_before-$offset)->offset($offset)->union($this->after_deadline_query->limit($offset+$limit-$count_before));
            } else {
                $query_sort = $this->after_deadline_query->limit($limit)->offset($offset-$count_before);
            }
        }

        return $query_sort->all($this->db);
    }
}
