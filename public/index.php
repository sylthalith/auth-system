<?php

use App\Controllers\AuthController;
use App\Router;

require '../vendor/autoload.php';

$router = new Router();

$router->addRoute('GET', '/', function () {
    echo '123';
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);