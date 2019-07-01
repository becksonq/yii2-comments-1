<?php

use yii\helpers\Html;
use yii\helpers\Json;
use beckson\comments\interfaces\CommentatorInterface;
use beckson\comments\models\Comment;
use beckson\comments\Module;
use beckson\comments\widgets\CommentFormWidget;
use kartik\icons\Icon;
use yii\widgets\ListView;

/** @var \beckson\comments\widgets\CommentListWidget $commentListWidget */
$commentListWidget = $this->context;

$comments = [];

echo Html::tag('h3', Yii::t('app', 'Comments'), ['class' => 'comment-title']);

/** @var $dataProvider \yii\data\ActiveDataProvider */
echo ListView::widget([

    'dataProvider' => $dataProvider,
    'options'      => ['class' => 'comments-list'],
    'layout'       => "{items}\n{pager}",
    'itemView'     =>
        function (Comment $comment, $index, $key, ListView $widget)
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
                            $avatar_html = Html::tag('div', Icon::show('user', ['framework' => Icon::FAR]), [
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
                            echo CommentFormWidget::widget([
                                'entity'  => $commentListWidget->entity,
                                'comment' => $comment,
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
                                echo Html::a(Icon::show('reply', ['framework' => Icon::FAS]) . ' ' . Yii::t('app', 'Reply'), '#', [
                                    'class'     => 'btn btn-info btn-xs',
                                    'data-role' => 'reply',
                                ]);
                            }

                            if ($comment->canUpdate()) {
                                echo Html::a(
                                    Icon::show('pencil-alt', ['framework' => Icon::FAS]) . ' ' . Yii::t('app', 'Edit'),
                                    '#',
                                    [
                                        'data-role' => 'edit',
                                        'class'     => 'btn btn-primary btn-xs',
                                    ]
                                );
                            }

                            if ($comment->canDelete()) {
                                echo Html::a(
                                    Icon::show('times-circle', ['framework' => Icon::FAR]) . ' ' . Yii::t('app', 'Delete'),
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

/** @var \beckson\comments\models\Comment $commentModel */
$commentModel = \Yii::createObject(Module::instance()->model('comment'));

if ($commentListWidget->showCreateForm && $commentModel::canCreate()) {
    echo Html::tag('h3', Yii::t('app', 'Add comment'), ['class' => 'comment-title']);

    echo CommentFormWidget::widget([
        'theme'   => $commentListWidget->theme,
        'entity'  => $commentListWidget->entity,
        'comment' => $commentModel,
        'anchor'  => $commentListWidget->anchorAfterUpdate,
    ]);
}

$commentListWidget
    ->getView()
    ->registerJs('jQuery("#' . $commentListWidget->options['id'] . '").yiiCommentsList(' . Json::encode($comments) . ');');
