<?php

use beckson\yii\module\Comments;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \beckson\yii\module\comments\widgets\CommentFormWidget $widget */
$widget = $this->context;

?>

<a name="commentcreateform"></a>
<div class="row comment-form">
    <div class="col-xs-12 col-sm-9 col-md-6 col-lg-4">
        <?php
        /** @var \yii\widgets\ActiveForm $form */
        $form = ActiveForm::begin();

        /** @var $commentCreateForm */
        echo Html::activeHiddenInput($commentCreateForm, 'id');

        if (\Yii::$app->getUser()->getIsGuest()) {
            echo $form->field($commentCreateForm, 'from')
                ->textInput();
        }

        $options = [];
        if ($widget->comment->isNewRecord) {
            $options['data-role'] = 'new-comment';
        }
        echo $form->field($commentCreateForm, 'text')
            ->textarea($options);

        ?>
        <div class="actions">
            <?php
            echo Html::submitButton(\Yii::t('app', 'Post comment'), [
                'class' => 'btn btn-primary',
            ]);
            echo Html::resetButton(\Yii::t('app', 'Cancel'), [
                'class' => 'btn btn-link',
            ]);
            ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>