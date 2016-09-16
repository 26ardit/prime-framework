<?php namespace core;

session_start();

require('../vendor/autoload.php');
require('sanitize.php');

$config = require('../config/config.php');

$view = new View(BASEPATH.'/app/views/');
$router = new Router();
