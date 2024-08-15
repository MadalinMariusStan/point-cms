<?php

/*
    Custom theme functions

    Note: we recommend you prefix all your functions to avoid any naming
    collisions or wrap your functions with if function_exists braces.
*/
function numeral($number, $hideIfOne = false)
{
    if ($hideIfOne === true && $number == 1) {
        return '';
    }

    $test = abs($number) % 10;
    $ext = 'th'; // Default to 'th'

    if (($number % 100 < 11) || ($number % 100 > 13)) {
        switch ($test) {
            case 1:
                $ext = 'st';
                break;
            case 2:
                $ext = 'nd';
                break;
            case 3:
                $ext = 'rd';
                break;
        }
    }

    return $number . $ext;
}


function count_words($str)
{
    return count(preg_split('/\s+/', strip_tags($str), null, PREG_SPLIT_NO_EMPTY));
}

function pluralise($amount, $str, $alt = '')
{
    return intval($amount) === 1 ? $str : $str . ($alt !== '' ? $alt : (__('site.s')));
}

function relative_time($date) {
    if(is_numeric($date)) $date = '@' . $date;

    $user_timezone = new DateTimeZone(Config::app('timezone'));
    $date = new DateTime($date, $user_timezone);

    // get current date in user timezone
    $now = new DateTime('now', $user_timezone);

    $elapsed = $now->format('U') - $date->format('U');

    if($elapsed <= 1) {
        return 'Just now';
    }

    $times = array(
        31104000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach($times as $seconds => $title) {
        $rounded = $elapsed / $seconds;

        if($rounded > 1) {
            $rounded = round($rounded);
            return $rounded . ' ' . pluralise($rounded, $title) . ' ago';
        }
    }
}


function total_articles()
{
    return total_posts();
}

