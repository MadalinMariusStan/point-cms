<?php

use System\config;
use System\database\query;
use System\uri;


/**
 *  Subscription statistics class
 */
class subscriber extends Base
{
    public static $table = 'subscribers';

    /**
     * Retrieves a subscriber by ID
     *
     * @param int $id post ID
     *
     * @return \post
     * @throws \Exception
     */
    public static function id($id)
    {
        return static::get('id', $id);
    }


}