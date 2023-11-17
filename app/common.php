<?php declare(strict_types=1);
require "config.php";
require "Database/DB.php";

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

function storage_path() {
   return __DIR__ . '/../Storage/';
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
    die();
}

function resp_success($data=null) {
   resp_json([
       'success' => 1,
       'msg' => '操作成功',
       'obj' => $data ?: new \stdClass()
   ]);
}
function resp_fail($message, $data=[]) {
    resp_json([
        'success' => 0,
        'msg' => $message,
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

function hash_make($password) {
    return password_hash($password, PASSWORD_BCRYPT, [
        'cost' => 12
    ]);
}

function hash_verify($password, $hash) {
    return password_verify($password, $hash);
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

function initXui() {
    $lockFile =  storage_path() . 'xui.lock';

    if (file_exists($lockFile)) {
        return;
    }

    // create databases;
    DB::instance()->exec("CREATE TABLE user (username CHAR(20), password CHAR(60))");
    // add default user
    DB::instance()->insert(
        "insert into user (username, password) values (?, ?)",
        ['admin', hash_make(ADMIN_PASSWORD)]
    );

    DB::instance()->exec(<<<'EOF'
CREATE table inbound (
   uid integer,
   up integer,
   down integer,
   total integer,
   remark char(50),
   enable integer,
   due_time char(30),
   listen char(25),
   port int,
   protocol char(20),
   settings text,
   stream_setting text,
   tag char(100),
   sniffing text
) 
EOF
);

    file_put_contents($lockFile, '');
}