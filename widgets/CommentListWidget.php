<?php

namespace beckson\yii\module\comments\widgets;

use beckson\yii\module\comments;
use yii\base\Widget;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

/**
 * Class CommentListWidget
 * @package beckson\yii\module\comments\widgets
 */
class CommentListWidget extends Widget
{

    /** @var string|null */
    public $theme;

    /** @var array */
    public $viewParams = [];

    /** @var array */
    public $options = ['class' => 'comments-widget'];

    /** @var string */
    public $entity;

    /** @var string */
    public $anchorAfterUpdate = '#comment-%d';

    /** @var array */
    public $pagination = [
        'pageParam'     => 'page',
        'pageSizeParam' => 'per-page',
        'pageSize'      => 20,
        'pageSizeLimit' => [1, 50],
    ];

    /** @var array */
    public $sort = [
        'defaultOrder' => [
            'id' => SORT_ASC,
        ],
    ];

    /** @var bool */
    public $showDeleted = true;

    /** @var bool */
    public $showCreateForm = true;

    public function init()
    {
        parent::init();

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        CommentListAsset::register($this->getView());

        $this->processDelete();

        /** @var comments\models\Comment $commentModel */
        $commentModel = \Yii::createObject(comments\Module::instance()->model('comment'));
        /** @var comments\models\queries\CommentQuery $commentsQuery */
        $commentsQuery = $commentModel::find()
            ->byEntity($this->entity);

        if (false === $this->showDeleted) {
            $commentsQuery->withoutDeleted();
        }

        $dataProvider = new ActiveDataProvider([
            'query'      => $commentsQuery->with(['author', 'lastUpdateAuthor']),
            'pagination' => $this->pagination,
            'sort'       => $this->sort,
        ]);

        $params = $this->viewParams;
        $params['dataProvider'] = $dataProvider;

        $content = $this->render('comment-list', $params);

        return Html::tag('div', $content, $this->options);
    }

    private function processDelete()
    {
        $delete = (int)\Yii::$app->getRequest()->get('delete-comment');
        if ($delete > 0) {

            /** @var comments\models\Comment $model */
            $model = \Yii::createObject(Comments\Module::instance()->model('comment'));

            /** @var comments\models\Comment $comment */
            $comment = $model::find()
                ->byId($delete)
                ->one();

            if ($comment->isDeleted()) {
                return;
            }

            if (!($comment instanceof comments\models\Comment)) {
                throw new NotFoundHttpException(\Yii::t('app', 'Comment not found.'));
            }

            if (!$comment->canDelete()) {
                throw new ForbiddenHttpException(\Yii::t('app', 'Access Denied.'));
            }

            $comment->deleted = $model::DELETED;
            $comment->update();
        }
    }

    /**
     * @inheritdoc
     */
    public function getViewPath()
    {
        if (empty($this->theme)) {
            return parent::getViewPath();
        } else {
            return \Yii::$app->getViewPath() . DIRECTORY_SEPARATOR . $this->theme;
        }
    }
}