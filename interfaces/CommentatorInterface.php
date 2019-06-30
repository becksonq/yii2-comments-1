<?php

namespace beckson\comments\interfaces;

/**
 * Interface CommentatorInterface
 * @package beckson\comments\interfaces
 */
interface CommentatorInterface
{
    /**
     * @return string|false
     */
    public function getCommentatorAvatar();

    /**
     * @return string
     */
    public function getCommentatorName();

    /**
     * @return string|false
     */
    public function getCommentatorUrl();
}