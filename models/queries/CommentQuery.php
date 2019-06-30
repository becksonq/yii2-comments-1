<?php

namespace beckson\comments\models\queries;

use beckson\comments;

/**
 * Class CommentQuery
 * @package beckson\comments\models\queries
 */
class CommentQuery extends \yii\db\ActiveQuery
{

    /**
     * @param integer|array $id
     * @return self
     */
    public function byId($id)
    {
        $this->andWhere(['id' => $id]);

        return $this;
    }

    /**
     * @param string|array $entity
     * @return self
     */
    public function byEntity($entity)
    {
        $this->andWhere(['entity' => $entity]);

        return $this;
    }

    /**
     * @return $this
     * @throws \yii\base\InvalidConfigException
     */
    public function withoutDeleted()
    {
        /** @var comments\models\Comment $commentModel */
        $commentModel = \Yii::createObject(comments\Module::instance()->model('comment'));

        $this->andWhere(['deleted' => $commentModel::NOT_DELETED]);

        return $this;
    }
}