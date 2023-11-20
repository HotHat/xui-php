<?php

namespace App\Controller;

use App\Database\DB;

class InboundController
{
    public function __construct()
    {
        checkLogin();
    }

    public function index()
    {
        render('xui/inbounds.php');
    }

    public function list()
    {
        $data = DB::instance()->query('select rowid as id, * from inbound order by id desc');
        $result = [];
        foreach ($data as $it) {
            $result[] = [
                'id' => $it['id'],
                'up' => $it['up'],
                'down' => $it['down'],
                'total' => $it['total'],
                'remark' => $it['remark'],
                'enable' => $it['enable'] === 1,
                'expiryTime' => intval($it['due_time']),
                'listen' => $it['listen'],
                'port' => $it['port'],
                'protocol' => $it['protocol'],
                'settings' => json_decode($it['settings']),
                'streamSettings' => json_decode($it['stream_setting']),
                'tag' => $it['tag'],
                'sniffing' => json_decode($it['sniffing']),
            ];
        }
        respSuccess($result);
    }

    public function add()
    {
        $s = $_POST;

        $bind = [
            $s['up'], $s['down'], $s['total'], $s['remark'], ($s['enable'] == 'true' ? 1 : 0),
            $s['expiryTime'], $s['listen'], $s['port'], $s['protocol'], json_encode($s['settings']),
            json_encode($s['streamSettings']), 'inbound-' . $s['port'], json_encode($s['sniffing'])
        ];
        DB::instance()->insert(
            'INSERT INTO inbound (up, down, total, remark, enable, due_time, listen, 
                        port, protocol, settings, stream_setting, tag, sniffing)
                     values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            $bind
        );

        $this->task('add', $bind);

        respSuccess();
    }

    public function update()
    {
        $s = $_POST;
        $bind = [
            $s['up'], $s['down'], $s['total'], $s['remark'], $s['enable'] == 'true' ? 1 : 0,
            $s['expiryTime'], $s['listen'], $s['port'], $s['protocol'], json_encode($s['settings'] ?? ''),
            json_encode($s['streamSettings'] ?? ''), 'inbound-' . $s['port'],
            json_encode($s['sniffing'] ?? '')
        ];

        $this->task('modify', $bind);

        $bind[] = intval($_GET['id']);
        DB::instance()->update(
            'UPDATE inbound SET 
                         up=?, down=?, total=?, remark=?, enable=?, 
                         due_time=?, listen=?, port=?, protocol=?, settings=?, 
                         stream_setting=?, tag=?, sniffing=? 
                     WHERE rowid=?',
            $bind
        );

        respSuccess();
    }

    public function del()
    {
        $id = intval($_GET['id']);

        $data = DB::instance()->fetchOne('SELECT * FROM inbound WHERE rowid=?', [$id]);

        DB::instance()->update('DELETE FROM inbound WHERE rowid=?', [$id]);

        $this->task('remove', $data);

        respSuccess();
    }

    private function task($type, $data) {
        DB::instance()->insert('INSERT INTO task (type, inbound) VALUES (?, ?)', [$type, json_encode($data)]);
    }
}