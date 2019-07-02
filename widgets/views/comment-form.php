<?php

use beckson\comments;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var \beckson\comments\widgets\CommentFormWidget $widget */
$widget = $this->context;

/** @var \yii\widgets\ActiveForm $form */
/** @var $commentCreateForm */
?>

<a name="commentcreateform"></a>
<div class="row comment-form">
    <div class="col-xs-12 col-sm-9 col-md-6">
        <?php
        $form = ActiveForm::begin();
        ?>


        <?= Html::activeHiddenInput($commentCreateForm, 'id') ?>

        <?php
        if (\Yii::$app->getUser()->getIsGuest()) {
            echo $form->field($commentCreateForm, 'from')->textInput();
        }

        $options = [];
        if ($widget->comment->isNewRecord) {
            $options['data-role'] = 'new-comment';
        }
        ?>

        <?= $form->field($commentCreateForm, 'text')->textarea($options) ?>

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