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

function assetUrl() {
    return '/assets/';
}
function baseUrl() {
    return '/';
}

function storagePath() {
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

function sessionFlush() {
    session_unset();
}

function respJson($data) {
    header('Content-Type: application/json');
    echo json_encode($data);
    die();
}

function respSuccess($data=null) {
   respJson([
       'success' => 1,
       'msg' => '操作成功',
       'obj' => $data ?: new \stdClass()
   ]);
}
function respFail($message, $data=[]) {
    respJson([
        'success' => 0,
        'msg' => $message,
        'obj' => $data ?: new \stdClass()
    ]);
}

function renderEnv($key) {
    return TemplateRender::get($key);
}

function render($path, $env = []) {
    foreach ($env as $k => $v) {
        TemplateRender::add($k, $v);
    }

    include TEMP_PATH . $path;
}

function authLogin($user) {
    session('auth_user', $user);
}
function auth_user() {
    return session('auth_user') ?? null;
}

function hashMake($password) {
    return password_hash($password, PASSWORD_BCRYPT, [
        'cost' => 12
    ]);
}

function hashVerify($password, $hash) {
    return password_verify($password, $hash);
}

function checkLogin() {
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
    $lockFile =  storagePath() . 'xui.lock';

    if (file_exists($lockFile)) {
        return;
    }

    // create databases;
    DB::instance()->exec("CREATE TABLE user (username CHAR(20), password CHAR(60))");
    // add default user
    DB::instance()->insert(
        "insert into user (username, password) values (?, ?)",
        ['admin', hashMake(ADMIN_PASSWORD)]
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

function registerExceptionHandler() {
    set_error_handler(function ($errno, $errStr, $errFile, $errLine) {
        if (!(error_reporting() & $errno)) {
            return false;
        }
        $errStr = htmlspecialchars($errStr);

        throw new \ErrorException($errStr, $errno, 1, $errFile, $errLine);
    });

    set_exception_handler(function (Throwable $exp) {
        file_put_contents(
            __DIR__ . '/../Storage/error.log',
            sprintf("%s: %s\n", date('Y-m-d H:i:s'), $exp->__toString()),
            FILE_APPEND
        );
    });
}