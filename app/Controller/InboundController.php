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
                'settings' => $it['settings'],
                'streamSettings' => $it['stream_setting'],
                'tag' => $it['tag'],
                'sniffing' => $it['sniffing'],
            ];
        }
        respSuccess($result, true);
    }

    public function add()
    {
        $s = $_POST;

        DB::beginTransaction();

        $bind = [
            'up' => $s['up'],
            'down' => $s['down'],
            'total' =>$s['total'],
            'remark' => $s['remark'],
            'enable' => ($s['enable'] == 'true' ? 1 : 0),
            'due_time' => $s['expiryTime'],
            'listen' => $s['listen'],
            'port' => $s['port'],
            'protocol' => $s['protocol'],
            'settings' => $s['settings'],
            'stream_setting' => $s['streamSettings'],
            'tag' => 'inbound-' . $s['port'],
            'sniffing' => $s['sniffing']
        ];
        DB::instance()->insert(
            'INSERT INTO inbound (up, down, total, remark, enable, due_time, listen, 
                        port, protocol, settings, stream_setting, tag, sniffing)
                     values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            array_values($bind)
        );

        $this->task('add', $bind);

        DB::commit();

        respSuccess();
    }

    public function update()
    {
        $s = $_POST;
        $bind = [
            'up' => $s['up'],
            'down' => $s['down'],
            'total' =>$s['total'],
            'remark' => $s['remark'],
            'enable' => ($s['enable'] == 'true' ? 1 : 0),
            'due_time' => $s['expiryTime'],
            'listen' => $s['listen'],
            'port' => $s['port'],
            'protocol' => $s['protocol'],
            'settings' => $s['settings'],
            'stream_setting' => $s['streamSettings'],
            'tag' => 'inbound-' . $s['port'],
            'sniffing' => $s['sniffing']
        ];

        DB::beginTransaction();

        $this->task('modify', $bind);

        $bind['rowid'] = intval($_GET['id']);

        DB::instance()->update(
            'UPDATE inbound SET 
                         up=?, down=?, total=?, remark=?, enable=?, 
                         due_time=?, listen=?, port=?, protocol=?, settings=?, 
                         stream_setting=?, tag=?, sniffing=? 
                     WHERE rowid=?',
            array_values($bind)
        );

        DB::commit();;

        respSuccess();
    }

    public function del()
    {
        $id = intval($_GET['id']);

        DB::beginTransaction();

        $data = DB::instance()->fetchOne('SELECT * FROM inbound WHERE rowid=?', [$id]);

        DB::instance()->update('DELETE FROM inbound WHERE rowid=?', [$id]);

        $this->task('remove', $data);

        DB::commit();

        respSuccess();
    }

    private function task($type, $data) {
        $config = [
            'inbounds' => [
                [
                    'listen' => $data['listen'] == '' ? null : $data['listen'],
                    'port' => $data['port'],
                    'protocol' => $data['protocol'],
                    'tag' => $data['tag'],
                    'settings' => json_decode($data['settings']),
                    'streamSettings' => json_decode($data['stream_setting']),
                    'sniffing' => !empty($data['sniffing']) ? json_decode($data['sniffing']) : null
                ]
            ]
        ];
        DB::instance()->insert('INSERT INTO task (type, inbound) VALUES (?, ?)', [$type, json_encode($config)]);
    }
}