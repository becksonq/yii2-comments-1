<?php

use rmrevin\yii\fontawesome\FA;
use beckson\yii\module\Comments;
use yii\helpers\Html;
use yii\helpers\Json;
use beckson\yii\module\comments\interfaces\CommentatorInterface;

/** @var \beckson\yii\module\comments\widgets\CommentListWidget $commentListWidget */
$commentListWidget = $this->context;

$comments = [];

echo Html::tag('h3', Yii::t('app', 'Comments'), ['class' => 'comment-title']);

echo yii\widgets\ListView::widget([
    /** @var $dataProvider */
    'dataProvider' => $dataProvider,
    'options'      => ['class' => 'comments-list'],
    'layout'       => "{items}\n{pager}",
    'itemView'     =>
        function (comments\models\Comment $comment, $index, yii\widgets\ListView $widget)
        use (&$comments, $commentListWidget) {
            ob_start();

            $formatter = Yii::$app->getFormatter();

            $author = $comment->author;

            $comments[$comment->id] = $comment->attributes;

            $options = [
                'data-comment' => $comment->id,
                'class'        => 'row comment',
            ];

            if ($index === 0) {
                Html::addCssClass($options, 'first');
            }

            if ($index === ($widget->dataProvider->getCount() - 1)) {
                Html::addCssClass($options, 'last');
            }

            if ($comment->isDeleted()) {
                Html::addCssClass($options, 'deleted');
            }

            ?>
            <div <?= Html::renderTagAttributes($options) ?>>
                <div class="col-lg-12">
                    <div class="author">
                        <?php
                        $avatar = false;
                        $name = Yii::t('app', 'Unknown author');
                        $url = false;

                        if (empty($author)) {
                            $name = empty($comment->from) ? $name : $comment->from;
                        } elseif ($author instanceof CommentatorInterface) {
                            $avatar = $author->getCommentatorAvatar();
                            $name = $author->getCommentatorName();
                            $name = empty($name) ? Yii::t('app', 'Unknown author') : $name;
                            $url = $author->getCommentatorUrl();
                        }

                        $name_html = Html::tag('strong', $name);

                        if (false === $avatar) {
                            $avatar_html = Html::tag('div', FA::icon('male'), [
                                'class' => 'avatar fake',
                                'title' => Yii::t('app', 'Unknown author'),
                            ]);
                        } else {
                            $avatar_html = Html::img($avatar, [
                                'class' => 'avatar',
                                'alt'   => Yii::t('app', 'Author avatar'),
                                'title' => $name,
                            ]);
                        }

                        if (false !== $url) {
                            echo Html::a($avatar_html, $url, ['target' => '_blank']);
                            echo Html::a($name_html, $url, ['target' => '_blank']);
                        } else {
                            echo $avatar_html;
                            echo $name_html;
                        }

                        if ((time() - $comment->created_at) > (86400 * 2)) {
                            echo Html::tag('span', $formatter->asDatetime($comment->created_at), ['class' => 'date']);
                        } else {
                            echo Html::tag('span', $formatter->asRelativeTime($comment->created_at),
                                ['class' => 'date']);
                        }
                        ?>
                    </div>
                    <div class="text">
                        <?php
                        if ($comment->isDeleted()) {
                            echo Yii::t('app', 'Comment was deleted.');
                        } else {
                            echo yii\helpers\Markdown::process($comment->text, 'gfm-comment');

                            if ($comment->isEdited()) {
                                echo Html::tag('small', Yii::t('app', 'Updated at {date-relative}', [
                                    'date'          => $formatter->asDate($comment->updated_at),
                                    'date-time'     => $formatter->asDatetime($comment->updated_at),
                                    'date-relative' => $formatter->asRelativeTime($comment->updated_at),
                                ]));
                            }
                        }
                        ?>
                    </div>
                    <?php
                    if ($comment->canUpdate() && !$comment->isDeleted()) {
                        ?>
                        <div class="edit">
                            <?php
                            echo Comments\widgets\CommentFormWidget::widget([
                                'entity'  => $commentListWidget->entity,
                                'Comment' => $comment,
                                'anchor'  => $commentListWidget->anchorAfterUpdate,
                            ]);
                            ?>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="actions">
                        <?php
                        if (!$comment->isDeleted()) {
                            if ($comment->canCreate()) {
                                echo Html::a(FA::icon('reply') . ' ' . Yii::t('app', 'Reply'), '#', [
                                    'class'     => 'btn btn-info btn-xs',
                                    'data-role' => 'reply',
                                ]);
                            }

                            if ($comment->canUpdate()) {
                                echo Html::a(
                                    FA::icon('pencil') . ' ' . Yii::t('app', 'Edit'),
                                    '#',
                                    [
                                        'data-role' => 'edit',
                                        'class'     => 'btn btn-primary btn-xs',
                                    ]
                                );
                            }

                            if ($comment->canDelete()) {
                                echo Html::a(
                                    FA::icon('times') . ' ' . Yii::t('app', 'Delete'),
                                    ['', 'delete-comment' => $comment->id],
                                    ['class' => 'btn btn-danger btn-xs']
                                );
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php

            return ob_get_clean();
        }
]);

/** @var \beckson\yii\module\comments\models\Comment $commentModel */
$commentModel = \Yii::createObject(Comments\Module::instance()->model('comment'));

if ($commentListWidget->showCreateForm && $commentModel::canCreate()) {
    echo Html::tag('h3', Yii::t('app', 'Add comment'), ['class' => 'comment-title']);

    echo Comments\widgets\CommentFormWidget::widget([
        'theme'   => $commentListWidget->theme,
        'entity'  => $commentListWidget->entity,
        'Comment' => $commentModel,
        'anchor'  => $commentListWidget->anchorAfterUpdate,
    ]);
}

$commentListWidget
    ->getView()
    ->registerJs('jQuery("#' . $commentListWidget->options['id'] . '").yiiCommentsList(' . Json::encode($comments) . ');');
