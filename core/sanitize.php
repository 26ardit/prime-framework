<?php

function escape($string)
{
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
}

function timeago($timestamp)
{
    $difference = time() - strtotime($timestamp);
    
    $periods = ['second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade'];
    $lengths = ['60', '60', '24', '7', '4.35', '12', '10'];

    for ($j = 0; $difference >= $lengths[$j]; $j++) {
        $difference /= $lengths[$j];
    }
    
    $difference = round($difference);
    
    if ($difference > 1) {
        $periods[$j] .= 's';
    }

    return "$difference $periods[$j] ago";
}

function get_group($group_id)
{
    return DB::run()->get('groups', ['id', '=', $group_id])->first()->name;
}

function dd($data)
{
    die(var_dump($data));
}
