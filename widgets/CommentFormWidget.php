<?php

namespace beckson\comments\widgets;

use beckson\comments\models\Comment;
use beckson\comments\forms\CommentCreateForm;
use beckson\comments\Module;

/**
 * Class CommentFormWidget
 * @package beckson\comments\widgets
 */
class CommentFormWidget extends \yii\base\Widget
{

    /** @var string|null */
    public $theme;

    /** @var string */
    public $entity;

    /** @var Comment */
    public $comment;

    /** @var string */
    public $anchor = '#comment-%d';

    /**
     * @inheritdoc
     */
    public function run()
    {
        CommentFormAsset::register($this->getView());

        /** @var CommentCreateForm $CommentCreateForm */
        $commentCreateFormClassData = Module::instance()->model(
            'commentCreateForm', [
                'Comment' => $this->comment,
                'entity' => $this->entity
            ]
        );

        $commentCreateForm = \Yii::createObject($commentCreateFormClassData);

        if ($commentCreateForm->load(\Yii::$app->getRequest()->post())) {
            if ($commentCreateForm->validate()) {
                if ($commentCreateForm->save()) {
                    \Yii::$app->getResponse()
                        ->refresh(sprintf($this->anchor, $commentCreateForm->Comment->id))
                        ->send();

                    exit;
                }
            }
        }

        return $this->render('comment-form', [
            'commentCreateForm' => $commentCreateForm,
        ]);
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