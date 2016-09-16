<?php namespace core;

class Redirect
{
    public static function to($location = null)
    {
        if ($location) {
            if ($location === 404) {
                header("HTTP/1.0 404 Not Found");
                include BASEPATH . '/app/views/errors/404.php';
            } else {
                header("Location: {$location}");
            }
            
            die();
        }
    }
}
