<?php

namespace beckson\yii\module\Comments\widgets;

/**
 * Class CommentFormAsset
 * @package beckson\yii\module\Comments\widgets
 */
class CommentFormAsset extends \yii\web\AssetBundle
{

    public $sourcePath = '@vendor/beckson/yii2-comments/widgets/_assets';

    public $css = [
        'comment-form.css',
    ];
}