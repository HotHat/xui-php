<?php declare(strict_types=1);

use App\Controller\DashboardController;
use App\Controller\InboundController;
use App\Controller\LoginController;

require "common.php";

initXui();

// start session
session_start();

$uri = $_SERVER['REQUEST_URI'];
$parseUrl = parse_url($uri);
$router = [];

$router['/login'] = action([LoginController::class, 'login']);

$router['/logout'] = action([LoginController::class, 'logout']);

$router['/login/submit'] = action([LoginController::class, 'submit']);

$router['/xui/'] = action([DashboardController::class, 'dashboard']);

$router['/xui/inbounds'] = action([InboundController::class, 'index']);
$router['/xui/inbound/list'] = action([InboundController::class, 'list']);
$router['/xui/inbound/add'] = action([InboundController::class, 'add']);
$router['/xui/inbound/update'] = action([InboundController::class, 'update']);
$router['/xui/inbound/del'] = action([InboundController::class, 'del']);

$router['/server/status'] = action([DashboardController::class, 'status']);



TemplateRender::add('request_uri', $parseUrl['path']);
if (isset($router[$parseUrl['path']])) {
    $action = $router[$parseUrl['path']];
    $action();
}