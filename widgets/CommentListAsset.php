<?php

namespace beckson\yii\module\comments\widgets;

/**
 * Class CommentListAsset
 * @package beckson\yii\module\comments\widgets
 */
class CommentListAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@vendor/beckson/yii2-comments/widgets/_assets';

    public $css = [
        'comment-list.css',
    ];

    public $js = [
        'comment-list.js',
    ];

    public $depends = [
        '\yii\web\YiiAsset',
        '\yii\web\JqueryAsset',
    ];
}