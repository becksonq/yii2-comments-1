<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \beckson\comments\models\Comment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="comments-form">
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'from')->textInput() ?>
    
    <?= $form->field($model, 'text')->textarea(['row' => 8]) ?>
    
    <?= $form->field($model, 'entity')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('comments', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>
