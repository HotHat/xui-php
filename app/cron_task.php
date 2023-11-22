<?php declare(strict_types=1);

use App\Command\XRun;
use App\Database\DB;

if (PHP_SAPI !== 'cli') { die(); }

require "common.php";

// init
initXui();

$tasks = DB::instance()->query('SELECT rowid,* FROM task ORDER BY rowid ASC');

$x = new XRun();

foreach ($tasks as $task) {
    switch ($task['type']) {
        case 'add': {
            $inbound = json_decode($task['inbound'], true);
            $tag = $inbound['inbounds'][0]['tag'];
            $x->createConfig($tag, $task['inbound']);
        } break;
        case 'modify': {
            $inbound = json_decode($task['inbound'], true);
            $tag = $inbound['inbounds'][0]['tag'];
            $x->modifyConfig($tag, $task['inbound']);
        } break;
        case 'remove': {
            $inbound = json_decode($task['inbound'], true);
            $tag = $inbound['inbounds'][0]['tag'];
            $x->removeConfig($tag);
        }
    }

    DB::instance()->exec('DELETE FROM task WHERE rowid=' . $task['rowid']);
}