<?php declare(strict_types=1);
if (PHP_SAPI !== 'cli') { die(); }

require "Command/XRun.php";
require "Database/db.php";
// $app = new XRun();

// $app->stats();
//
/*
$db->query( <<<'EOF'
 create table if not exists user (id int auto increment primary key, name char(20), password char(32), created_at char(20))
EOF
);
*/

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

$data = $db->query('select rowid as id,* from user where rowid=1');
print_r($data);
// print_r($db->lastError());
