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
        // print_r($this->getDiskUsage());
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
        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);

        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);

        $swap = explode(" ", $free_arr[2]);
        $swap = array_filter($swap);
        $swap = array_merge($swap);

        return [
            'mem' => [
                'current' => $mem[2] * 1024,
                'total' => $mem[1] * 1024,
            ],
            'swap'=>[
                'current' => $swap[2] ? ($swap[2] * 1024) : 0,
                'total' => $swap[1] ? ($swap[1] * 1024) : 0,
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
        $data = shell_exec('ifconfig');
        $lines = explode("\n", $data);

        $up = 0;
        $down = 0;
        foreach ($lines as $line) {
            if (preg_match('/RX packets/', $line, $match)) {
                if (preg_match('/bytes (\d+)/', $line, $mat)) {
                    $down += intval($mat[1]);
                }
            }

            if (preg_match('/TX packets/', $line, $match)) {
                if (preg_match('/bytes (\d+)/', $line, $mat)) {
                    $up += intval($mat[1]);
                }
            }
        }

        return [
            'sent' => $up,
            'recv' => $down
        ];
        /*
        $data = shell_exec('ip -s link');
        $lines = explode("\n", $data);

        $upL = [];
        $downL = [];
        foreach ($lines as $k => $line) {
            if (preg_match('/RX:/', $line, $match)) {
                $downL[] = $k +1;
            }

            if (preg_match('/TX:/', $line, $match)) {
                $upL[] = $k+1;
            }
        }

        $sent = $recv = 0;
        foreach ($upL as $k) {
            $line = $lines[$k];
            $arr = preg_split('/\s+/', $line);
            $sent += intval($arr[1]);
        }

        foreach ($downL as $k) {
            $line = $lines[$k];
            $arr = preg_split('/\s+/', $line);
            $recv += intval($arr[1]);
        }


        */
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