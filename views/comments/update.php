<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \beckson\comments\models\Comment */

$this->title = Yii::t('common', 'Update Comment: ' . $model->title, [
    'nameAttribute' => '' . $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('common', 'Update');
?>
<div class="comments-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
