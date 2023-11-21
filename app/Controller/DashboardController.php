<?php

namespace App\Controller;

class DashboardController
{
    public function __construct()
    {
        checkLogin();
    }

    public function dashboard() {
        // $top = shell_exec('cat /proc/uptime');
        // var_dump($top);
        // print_r($this->getTraffic());
        // die();
        render('xui/index.php');
    }

    private function getCpuUsage(){
        $load = sys_getloadavg();
        return $load;
    }

    private function getDiskUsage() {
        $data = shell_exec('df');
        $d = explode("\n", $data);

        foreach ($d as $line) {
            $l = preg_split('/\s+/', $line);
            if ($l[5] == '/') {
                return [
                    'used' => $l[2] * 1024,
                    'total' => $l[3] * 1024
                ];
            }
        }
        return [
            'used' => 0,
            'total' => 0
        ];
    }

    private function getTcpCount() {
        $data = shell_exec('netstat -ant | wc -l');
        return intval($data);
    }
    private function getUdpCount() {
        $data = shell_exec('netstat -anu | wc -l');
        return intval($data);
    }

    private function getMemoryUsage(){
        $content = file_get_contents('/proc/meminfo');
        $lines = explode("\n", $content);
        $m = [];
        foreach ($lines as $line) {
            if (preg_match('/MemTotal:(\s+)(\d+)/', $line, $matches)) {
                $m['mem']['total'] = $matches[2] * 1024;
            }
            if (preg_match('/MemFree:(\s+)(\d+)/', $line, $matches)) {
                $m['mem']['free'] = $matches[2] * 1024;
            }
            if (preg_match('/SwapTotal:(\s+)(\d+)/', $line, $matches)) {
                $m['swap']['total'] = $matches[2] * 1024;
            }
            if (preg_match('/SwapFree:(\s+)(\d+)/', $line, $matches)) {
                $m['swap']['free'] = $matches[2] * 1024;
            }

        }
        return [
            'mem' => [
                'current' => $m['mem']['total'] - $m['mem']['free'],
                'total' => $m['mem']['total'],
            ],
            'swap'=>[
                'current' => $m['swap']['total'] - $m['swap']['free'],
                'total' => $m['swap']['total'],
            ]
        ];
    }

    public function getUptime() {
        $data = shell_exec('cat /proc/uptime');
        $d = explode(' ', $data);
        return intval($d[0]);
    }

    public function getNetIo() {
        $s = $this->getTraffic();
        sleep(1);
        $e = $this->getTraffic();
        return [
            'up' => $e['sent'] - $s['sent'],
            'down' => $e['recv'] - $s['recv'],
        ];
    }

    public function getTraffic() {
        $content = file_get_contents('/proc/net/dev');
        $lines = explode("\n", $content);
        $up = 0;
        $down = 0;
        // print_r($lines);
        foreach ($lines as $line) {
            $blocks = preg_split('/\s+/', $line);
            if (count($blocks) == 18) {
                $down += $blocks[2];
                $up += $blocks[10];
            }
        }

        return [
            'sent' => $up,
            'recv' => $down
        ];
    }
    public function status() {
        $sw = $this->getMemoryUsage();
        $cpu = $this->getCpuUsage();
        $disk = $this->getDiskUsage();
        $netIo = $this->getNetIo();
        $traffic = $this->getTraffic();
        $uptime = $this->getUptime();

        respSuccess([
            'cpu' => $cpu[0],
            'disk' => [
                'current' => $disk['used'],
                'total' => $disk['total']
            ],
            'loads' => $cpu,

            'mem' => $sw['mem'],
            'swap' => $sw['swap'],

            "netIO" => [
                "up" => $netIo['up'],
                "down" => $netIo['down']
            ],
            "netTraffic" => [
                "sent" => $traffic['sent'],
                "recv" => $traffic['recv']
            ],
            'tcpCount' => $this->getTcpCount(),
            'udpCount' => $this->getUdpCount(),
            'uptime' => $uptime,
            'xray' => [
                'state' => 'running'
            ]
        ]);
    }
}