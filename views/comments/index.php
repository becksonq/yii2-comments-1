<?php

use yii\widgets\Pjax;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel \beckson\comments\models\queries\CommentQuery */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Comments');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-index">

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Comment'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel'  => $searchModel,
            'columns'      => [
//                'id',
                'text',
                'entity',
                'from',
                'created_by',
                'updated_by',
                'created_at:dateTime',
                'updated_at:dateTime',
                ['class' => 'yii\grid\ActionColumn'],
            ],
        ]); ?></div>
    <?php Pjax::end(); ?>
</div>
