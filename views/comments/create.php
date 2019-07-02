<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model \beckson\comments\models\Comment */

$this->title = Yii::t('common', 'Create Comment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('common', 'Comments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-create">

    <h4><?= Html::encode($this->title) ?></h4>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
