<?php declare(strict_types=1);

use App\Config;
use App\Controller\DashboardController;
use App\Controller\InboundController;
use App\Controller\LoginController;
use App\Controller\SettingController;

require "common.php";

initXui();

// start session
session_start();

$reqUri = $_SERVER['REQUEST_URI'];
$parseUri = parse_url($reqUri);
$uri = $parseUri['path'];
if (Config::PROXY_PREFIX->value !== '') {
    $vl = strlen(Config::PROXY_PREFIX->value);
    if (substr($uri, 0, $vl) == Config::PROXY_PREFIX->value) {
        $uri = substr($uri, $vl);
    }
}

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

$router['/xui/setting'] = action([SettingController::class, 'index']);
$router['/xui/setting/all'] = action([SettingController::class, 'all']);
$router['/xui/setting/update'] = action([SettingController::class, 'update']);
$router['/xui/setting/updateUser'] = action([SettingController::class, 'updateUser']);

$router['/server/status'] = action([DashboardController::class, 'status']);


TemplateRender::add('request_uri', $uri);
if (isset($router[$uri])) {
    $action = $router[$uri];
    $action();
}