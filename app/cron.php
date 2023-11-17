<?php declare(strict_types=1);
if (PHP_SAPI !== 'cli') { die(); }

require "common.php";
require "Command/XRun.php";

// init tables
initXui();
registerExceptionHandler();

$json = '{"stat":[{"name":"inbound>>>inbound-7532>>>traffic>>>downlink"},{"name":"inbound>>>tcp>>>traffic>>>uplink"},{"name":"inbound>>>tcp>>>traffic>>>downlink"},{"name":"inbound>>>api>>>traffic>>>uplink","value":"3174"},{"name":"inbound>>>api>>>traffic>>>downlink","value":"2267"},{"name":"user>>>vless001@33448899.com>>>traffic>>>uplink","value":"6250586"},{"name":"user>>>vless001@33448899.com>>>traffic>>>downlink","value":"1686444639"},{"name":"inbound>>>inbound-7532>>>traffic>>>uplink"}]}';

print_r(json_decode($json, true));

trigger_error('test error happen');
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
