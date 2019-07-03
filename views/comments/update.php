<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \beckson\comments\models\Comment */

$this->title = Yii::t('app', 'Update Comment: ' . $model->id, [
    'nameAttribute' => '' . $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="comments-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
