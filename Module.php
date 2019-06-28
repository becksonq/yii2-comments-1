<?php

namespace beckson\yii\module\comments;

use beckson\yii\module\comments\forms\CommentCreateForm;
use beckson\yii\module\comments\models\Comment;
use beckson\yii\module\comments\models\queries\CommentQuery;
use yii\helpers\ArrayHelper;
use yii\base\Module as BaseModule;

/**
 * Class Comments
 * @package beckson\yii\module
 */
class Module extends BaseModule
{

    /** @var string module name */
    public static $moduleId = 'comments';

    /** @var string|null */
    public $userIdentityClass = null;

    /** @var bool */
    public $useRbac = true;

    /**
     * Array that will store the models used in the package
     * e.g. :
     * [
     *     'Comment' => 'frontend/models/comments/CommentModel'
     * ]
     *
     * The classes defined here will be merged with getDefaultModels()
     * having he manually defined by the user preference.
     *
     * @var array
     */
    public $modelMap = [];

    public function init()
    {
        parent::init();

        if ($this->userIdentityClass === null) {
            $this->userIdentityClass = \Yii::$app->getUser()->identityClass;
        }

        // Merge the default model classes
        // with the user defined ones.
        $this->defineModelClasses();
    }

    /**
     * @return static
     */
    public static function instance()
    {
        return \Yii::$app->getModule(static::$moduleId);
    }

    /**
     * Merges the default and user defined model classes
     * Also let's the developer to set new ones with the
     * parameter being those the ones with most preference.
     *
     * @param array $modelClasses
     */
    public function defineModelClasses($modelClasses = [])
    {
        $this->modelMap = ArrayHelper::merge(
            $this->getDefaultModels(),
            $this->modelMap,
            $modelClasses
        );
    }

    /**
     * Get default model classes
     */
    protected function getDefaultModels()
    {
        return [
            'Comment' => Comment::className(),
            'CommentQuery' => CommentQuery::className(),
            'CommentCreateForm' => CommentCreateForm::className()
        ];
    }

    /**
     * Get defined className of model
     *
     * Returns string or array compatible
     * with the Yii::createObject method.
     *
     * @param string $name
     * @param array $config // You should never send an array with a key defined as "class" since this will
     *                      // overwrite the main className defined by the system.
     * @return string|array
     */
    public function model($name, $config = [])
    {
        $modelData = $this->modelMap[ucfirst($name)];

        if (!empty($config)) {
            if (is_string($modelData)) {
                $modelData = ['class' => $modelData];
            }

            $modelData = ArrayHelper::merge(
                $modelData,
                $config
            );
        }

        return $modelData;
    }

}