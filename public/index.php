<?php

use App\Controllers\UserController;
use App\Exceptions\ValidationException;
use App\Routes\Router;

require_once "../vendor/autoload.php";




Router::get('/', UserController::class, 'index');
Router::post('/store', UserController::class, 'store');
Router::post('/update', UserController::class, 'update');
Router::delete('/delete', UserController::class, 'destroy');


Router::run();