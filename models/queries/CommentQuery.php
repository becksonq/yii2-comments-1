<?php

namespace beckson\comments\models\queries;

use beckson\comments\models\Comment;
use beckson\comments\Module;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class CommentQuery
 * @package beckson\comments\models\queries
 */
class CommentQuery extends Comment
{
    /**
     * @param $id
     * @param ActiveQuery $query
     * @return mixed
     */
    public function byId($id, ActiveQuery $query)
    {
        return $query->andWhere(['id' => $id]);
    }

    /**
     * @param $entity
     * @param ActiveQuery $query
     * @return ActiveQuery
     */
    public function byEntity($entity, ActiveQuery $query)
    {
        return $query->andWhere(['entity' => $entity]);
    }

    /**
     * @param ActiveQuery $query
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function withoutDeleted(ActiveQuery $query)
    {
        /** @var Comment $commentModel */
        $commentModel = \Yii::createObject(Module::instance()->model('comment'));

        return $query->andWhere(['deleted' => $commentModel::NOT_DELETED]);
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