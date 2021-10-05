<?php
require_once __DIR__.'/../vendor/autoload.php';

use App\Controllers\Auth\AuthController;
use App\Controllers\HomeController;
use App\Core\App;

$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$app = new App(dirname(__DIR__));

$app->router->get('/',[HomeController::class,'home']);

$app->router->get('/login',[AuthController::class,'login']);
$app->router->post('/login',[AuthController::class,'loginPost']);

$app->router->get('/register',[AuthController::class,'register']);
$app->router->post('/register',[AuthController::class,'registerPost']);

$app->router->get('/profile',[AuthController::class,'profile']);

$app->router->get('/logout',[AuthController::class,'logout']);

$app->run();