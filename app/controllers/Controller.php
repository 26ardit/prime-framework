<?php namespace app\controllers;

use core\View;

class Controller
{
    public function __construct()
    {
        $this->view = new View(BASEPATH.'/app/views/');
    }

    public function display($name, $data = [])
    {
        $this->view->display($name, $data);
    }
}
