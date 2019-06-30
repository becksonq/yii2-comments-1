<?php

namespace beckson\comments\widgets;

/**
 * Class CommentFormAsset
 * @package beckson\comments\widgets
 */
class CommentFormAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/beckson/yii2-comments/widgets/_assets';

    public $css = [
        'comment-form.css',
    ];
}