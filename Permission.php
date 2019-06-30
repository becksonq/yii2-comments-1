<?php

namespace beckson\comments;

/**
 * Class Permission
 * @package beckson\comments
 */
class Permission
{
    const CREATE        = 'comments.create';
    const UPDATE        = 'comments.update';
    const UPDATE_OWN    = 'comments.update.own';
    const DELETE        = 'comments.delete';
    const DELETE_OWN    = 'comments.delete.own';
}