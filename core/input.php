<?php namespace core;

class Input
{
    public static function exists($type = 'post')
    {
        switch ($type) {
            case 'post':
                return (!empty($_POST));
                break;
            case 'get':
                return (!empty($_GET));
                break;
            case 'files':
                return (!empty($_FILES));
                break;
            default:
                return false;
                break;
        }
    }

    public static function get($item)
    {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } elseif (isset($_GET[$item])) {
            return $_GET[$item];
        } elseif (isset($_FILES[$item])) {
            return $_FILES[$item];
        }

        return '';
    }
}
