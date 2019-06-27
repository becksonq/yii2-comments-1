<?php

namespace beckson\yii\module\comments\models;

use beckson\yii\module\comments;

/**
 * Class Comment
 * @package beckson\yii\module\comments\models
 *
 * @property integer $id
 * @property string $entity
 * @property string $from
 * @property string $text
 * @property integer $deleted
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property \yii\db\ActiveRecord $author
 * @property \yii\db\ActiveRecord $lastUpdateAuthor
 *
 * @method queries\CommentQuery hasMany(string $class, array $link) see BaseActiveRecord::hasMany() for more info
 * @method queries\CommentQuery hasOne(string $class, array $link) see BaseActiveRecord::hasOne() for more info
 */
class Comment extends \yii\db\ActiveRecord
{
    const NOT_DELETED   = 0;
    const DELETED       = 1;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            \yii\behaviors\BlameableBehavior::className(),
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
            [['from', 'text'], 'string'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['deleted'], 'boolean'],
            [['deleted'], 'default', 'value' => self::NOT_DELETED],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => \Yii::t('app', 'ID'),
            'entity'     => \Yii::t('app', 'Entity'),
            'from'       => \Yii::t('app', 'Comment author'),
            'text'       => \Yii::t('app', 'Text'),
            'created_by' => \Yii::t('app', 'Created by'),
            'updated_by' => \Yii::t('app', 'Updated by'),
            'created_at' => \Yii::t('app', 'Created at'),
            'updated_at' => \Yii::t('app', 'Updated at'),
        ];
    }

    /**
     * @return bool
     */
    public function isEdited()
    {
        return $this->created_at !== $this->updated_at;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted === self::DELETED;
    }

    /**
     * @return bool
     */
    public static function canCreate()
    {
        return comments\Module::instance()->useRbac === true
            ? \Yii::$app->getUser()->can(comments\Permission::CREATE)
            : true;
    }

    /**
     * @return bool
     */
    public function canUpdate()
    {
        $user = \Yii::$app->getUser();

        return comments\Module::instance()->useRbac === true
            ? \Yii::$app->getUser()->can(comments\Permission::UPDATE) || \Yii::$app->getUser()->can(comments\Permission::UPDATE_OWN, ['Comment' => $this])
            : $user->isGuest ? false : $this->created_by === $user->id;
    }

    /**
     * @return bool
     */
    public function canDelete()
    {
        $user = \Yii::$app->getUser();

        return comments\Module::instance()->useRbac === true
            ? \Yii::$app->getUser()->can(comments\Permission::DELETE) || \Yii::$app->getUser()->can(comments\Permission::DELETE_OWN, ['Comment' => $this])
            : $user->isGuest ? false : $this->created_by === $user->id;
    }

    /**
     * @return queries\CommentQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(comments\Module::instance()->userIdentityClass, ['id' => 'created_by']);
    }

    /**
     * @return queries\CommentQuery
     */
    public function getLastUpdateAuthor()
    {
        return $this->hasOne(comments\Module::instance()->userIdentityClass, ['id' => 'updated_by']);
    }

    /**
     * @return object|\yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function find()
    {
        return \Yii::createObject(
            comments\Module::instance()->model('commentQuery'),
            [get_called_class()]
        );
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }
}