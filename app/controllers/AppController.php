<?php namespace app\controllers;

use core\Input;
use app\models\User;

class AppController extends Controller
{
    public function index()
    {
        // $user = new User;
        // $user = $user->where(['id', 1])->first();
        // $name = $user->name;

        // $name = Input::get('name');

        $name = 'Prime Framework';
        
        $this->display('index', compact('name'));
    }
}
