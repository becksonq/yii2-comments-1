<?php

namespace beckson\comments\models\queries;

use beckson\comments\models\Comment;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\base\Model;

/**
 * Class CommentQuery
 * @package beckson\comments\models\queries
 */
class CommentQuery extends Comment
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['entity', 'from', 'text'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param $id
     * @return ActiveQuery
     */
    public function findById($id)
    {
        $query = Comment::find()
            ->where(['id' => $id]);

        return $query;
    }

    /**
     * @param $entity
     * @return object|ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function findByEntity($entity)
    {
        $query = Comment::find()
            ->where(['entity' => $entity]);

        return $query;
    }

    /**
     * @param ActiveQuery $query
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function withoutDeleted(ActiveQuery $query)
    {
        return $query->andWhere(['deleted' => Comment::NOT_DELETED]);
    }

    public function search($params)
    {
        $query = Comment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => [
                'defaultOrder' => ['id' => SORT_DESC,],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id'         => $this->id,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'entity', $this->entity])
            ->andFilterWhere(['like', 'from', $this->from])
            ->andFilterWhere(['like', 'text', $this->text]);

        return $dataProvider;
    }
}