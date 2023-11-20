<?php declare(strict_types=1);

use App\Database\DB;

if (PHP_SAPI !== 'cli') { die(); }

require "common.php";

print_r(shell_exec('free'));

/*
// init tables
initXui();

$app = new XRun();

$data = $app->stats();

$js = json_decode($data, true);
// print_r($js);

foreach ($js['stat'] as $it) {
    [$type, $user, $traffic, $direct] = explode('>>>', $it['name']);

    if (preg_match('/inbound-(\d)+/', $user, $matches)) {
        var_dump($it);

        if ($direct === 'uplink') {
            // save to db

        } else if ($direct === 'uplink') {
            // save to db
        }
    }

}
*/
// DB::instance()->update('delete from inbound where rowid=8');

// echo (include __DIR__ . '/../template/login.php');
// $app = new XRun();

// $app->stats();

/*
$db->query( <<<'EOF'
 create table if not exists user (id int auto increment primary key, name char(20), password char(32), created_at char(20))
EOF
);

$db = new DB(__DIR__ . '/Database/', 'db.sqlite');

$db->exec("create table if not exists user (id ROWID, name char(20), password char(32), created_at char(20))");
// $data = $db->query('select * from user where id=?', [5]);
// var_dump($data);


// $cnt = $db->update("update user set password=? where id=?", ['456789', 3]);
// var_dump($cnt);
//
//
// $data = $db->query('select * from user where id=?', [3]);
// var_dump($data);
// $id = $db->insert("insert into user (name, password, created_at) values (?, ?, ?)", ['hello', 'word', '2023-11-17 14:50:22']);

// var_dump('last insert id: '. $id);

$data = DB::instance()->fetchOne('select rowid as id, * from user');
print_r($data);
// print_r($db->lastError());

*/
