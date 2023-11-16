<?php declare(strict_types=1);


const TEMP_PATH = __DIR__ . '/../template/';

class TemplateRender {
    static $env = [];

    static function add($key, $value) {
        self::$env[$key] = $value;
    }

    static function get($key) {
        return self::$env[$key] ?? '';
    }
}

function e($html) {
    echo $html;
}

function asset_url() {
    return '/assets/';
}
function base_url() {
    return '/';
}

function redirect($url) {
    header("Location: " . $url);
    die();
}

function session($key, $value=null) {
    if (!$value) {
        return $_SESSION[$key];
    }
    $_SESSION[$key] = $value;
}

function session_flush() {
    session_unset();
}

function resp_json($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
}

function resp_success($data) {
   resp_json([
       'success' => 1,
       'msg' => '操作成功',
       'obj' => $data ?: new \stdClass()
   ]);
}

function render_env($key) {
    return TemplateRender::get($key);
}

function render($path, $env = []) {
    foreach ($env as $k => $v) {
        TemplateRender::add($k, $v);
    }

    include TEMP_PATH . $path;
}

function auth_login($user) {
    session('auth_user', $user);
}
function auth_user() {
    return session('auth_user') ?? null;
}

function check_login() {
   $user = auth_user();
   if (!$user) {
       redirect('/login');
   }
}

function action($callArray) {
    [$class, $method] = $callArray;
    return function () use ($class, $method) {
        $instance = new $class;
        $instance->{$method}();
    };
}