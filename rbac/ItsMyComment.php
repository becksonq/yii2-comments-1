<?php

namespace beckson\yii\module\comments\rbac;

use yii\rbac\Rule;

/**
 * Class ItsMyComment
 * @package beckson\yii\module\comments\rbac
 */
class ItsMyComment extends Rule
{

    public $name = 'comments.its-my-comment';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return (int)$user === (int)$params['Comment']->created_by;
    }
}