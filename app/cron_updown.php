<?php declare(strict_types=1);

use App\Command\XRun;
use App\Database\DB;

if (PHP_SAPI !== 'cli') { die(); }

require "common.php";

// init
initXui();

$inbounds = DB::instance()->query('SELECT rowid,* FROM inbound ORDER BY rowid ASC');

$x = new XRun();

foreach ($inbounds as $inbound) {
    if ($inbound['enable']) {
        // timeout
        if ($inbound['due_time'] != 0 && (time() * 1000) > $inbound['due_time']) {
            // echo 'timeout', PHP_EOL;
            // print_r($inbound);
            $x->delInbound($inbound['tag']);
            DB::instance()->update('UPDATE inbound set enable=? where rowid=?', [0, $inbound['rowid']]);
        } else {
            // flow over
            if ($inbound['total'] > 0 && $inbound['total'] <= ($inbound['up'] + $inbound['down'])) {
                // echo 'flow over', PHP_EOL;
                // print_r($inbound);
                $x->delInbound($inbound['tag']);
                DB::instance()->update('UPDATE inbound set enable=? where rowid=?', [0, $inbound['rowid']]);
            }
        }
    }


}