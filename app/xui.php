<?php declare(strict_types=1);

use App\Controller\DashboardController;
use App\Controller\InboundController;
use App\Controller\LoginController;

require "common.php";


spl_autoload_register(function ($class) {
    $class = str_replace('App\\Controller\\', '', $class);
    $class = str_replace('\\', '/', $class);
    include __DIR__ . '/Controller/' . $class . '.php';
});


$uri = trim($_SERVER['REQUEST_URI'], '/');

$router = [];

$router['login'] = action([LoginController::class, 'login']);

$router['logout'] = action([LoginController::class, 'logout']);

$router['login/submit'] = action([LoginController::class, 'submit']);

$router['xui'] = action([DashboardController::class, 'dashboard']);

$router['xui/inbounds'] = action([InboundController::class, 'index']);

$router['server/status'] = action([DashboardController::class, 'status']);


// start session
session_start();

if (isset($router[$uri])) {
    $action = $router[$uri];
    $action();
}