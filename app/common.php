<?php declare(strict_types=1);

use App\Config;
use App\Database\DB;

require "Config.php";

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

function url($uri, $params=[]) {
    return Config::APP_URL->value . '/' . ltrim($uri, '/') . '?' . http_build_query($params);
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
        return $_SESSION[$key] ?? null;
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

function respSuccess($data=null, $isArray=false) {
   respJson([
       'success' => true,
       'msg' => '',
       'obj' => $data ?: ($isArray ? [] : new \stdClass())
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

function renderString($filename) {
    $path = TEMP_PATH . $filename;
    if (is_file($path)) {
        ob_start();
        include $path;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    return false;
}

function authLogin($user) {
    session('auth_user', $user);
}
function auth_user() {
    return session('auth_user');
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
    registerExceptionHandler();
    registerAutoload();

    $lockFile =  storagePath() . 'xui.lock';

    if (file_exists($lockFile)) {
        return;
    }

    // create databases;
    DB::instance()->exec("CREATE TABLE user (username CHAR(20), password CHAR(60))");
    // add default user
    DB::instance()->insert(
        "insert into user (username, password) values (?, ?)",
        ['admin', hashMake(Config::ADMIN_PASSWORD->value)]
    );

    // add/remove/modify config task
    DB::instance()->exec(
        "CREATE TABLE task (type CHAR(20), inbound text)",
    );

    DB::instance()->exec(<<<'EOF'
CREATE table inbound (
   up NUMERIC,
   down NUMERIC,
   total NUMERIC,
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
    // REPLACE INTO positions (title, min_salary) VALUES('DBA', 170000);
    // DB::instance()->exec('CREATE UNIQUE INDEX idx_tag ON inbound (tag)');

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

function registerAutoload() {
    spl_autoload_register(function ($class) {
        $class = str_replace('\\', '/', $class);
        $class = str_replace('App', 'app', $class);

        if (file_exists(__DIR__ . '/../' . $class . '.php')) {
            include __DIR__ . '/../' . $class . '.php';
        }
    });
}