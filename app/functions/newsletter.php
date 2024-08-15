<?php

/********************************
 *  Theme functions for newsletter
 ********************************/

use System\config;
use System\database\query;
use System\input;

function subscribe_link()
{
    return Uri::to('subscribe');
}