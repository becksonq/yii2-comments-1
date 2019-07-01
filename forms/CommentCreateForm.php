<?php

namespace beckson\comments\forms;

use beckson\comments;
use beckson\comments\Module;
use yii\base\Model as BaseModel;
use beckson\comments\models\Comment;

/**
 * Class CommentCreateForm
 * @package beckson\comments\forms
 */
class CommentCreateForm extends BaseModel
{

    public $id;
    public $entity;
    public $from;
    public $text;

    /** @var comments\models\Comment */
    public $comment;

    public function init()
    {
        $comment = $this->comment;

        if (false === $this->comment->isNewRecord) {
            $this->id = $comment->id;
            $this->entity = $comment->entity;
            $this->from = $comment->from;
            $this->text = $comment->text;
        } elseif (!\Yii::$app->getUser()->getIsGuest()) {
            $user = \Yii::$app->getUser()->getIdentity();

            $this->from = $user instanceof comments\interfaces\CommentatorInterface
                ? $user->getCommentatorName()
                : null;
        }
    }

    /**
     * @return array
     */
    public function rules()
    {
        /** @var  $commentModelClassName */
        $commentModelClassName = Module::instance()->model('comment');

        return [
            [['entity', 'text'], 'required'],
            [['entity', 'from', 'text'], 'string'],
            [['id'], 'integer'],
            [['id'], 'exist', 'targetClass' => $commentModelClassName, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'entity' => \Yii::t('app', 'Entity'),
            'from'   => \Yii::t('app', 'Your name'),
            'text'   => \Yii::t('app', 'Text'),
        ];
    }

    /**
     * @return bool
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     */
    public function save()
    {
        $comment = $this->comment;

        $commentModelClassName = comments\Module::instance()->model('comment');

        if (empty($this->id)) {
            $comment = \Yii::createObject($commentModelClassName);
        } elseif ($this->id > 0 && $comment->id !== $this->id) {
            /** @var Comments\models\Comment $CommentModel */
            $commentModel = \Yii::createObject($commentModelClassName);
            $comment = $commentModel::find()
                ->byId($this->id)
                ->one();

            if (!($comment instanceof Comment)) {
                throw new \yii\web\NotFoundHttpException;
            }
        }

        $comment->entity = $this->entity;
        $comment->from = $this->from;
        $comment->text = $this->text;

        $result = $comment->save();

        if ($comment->hasErrors()) {
            foreach ($comment->getErrors() as $attribute => $messages) {
                foreach ($messages as $mes) {
                    $this->addError($attribute, $mes);
                }
            }
        }

        $this->comment = $comment;

        return $result;
    }
}