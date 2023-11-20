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
        // $this->getDiskUsage();
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
        $l = preg_split('/\W+/', $d[1]);
        return [
            'used' => $l[2],
            'total' => $l[3]
        ];
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
                'current' => $mem[2],
                'total' => $mem[1],
            ],
            'swap'=>[
                'current' => $swap[2],
                'total' => $swap[1],
            ]
        ];
    }

    public function getUptime() {
        $data = shell_exec('cat /proc/uptime');
        $d = explode(' ', $data);
        return intval($d[0]);
    }
    public function status() {
        $sw = $this->getMemoryUsage();
        $cpu = $this->getCpuUsage();
        $disk = $this->getDiskUsage();

        respSuccess([
            'cpu' => $cpu[0],
            'disk' => [
                'current' => $disk['used'],
                'total' => $disk['total']
            ],
            'loads' => $cpu,

            'mem' => $sw['mem'],
            'swap' => $sw['swap'],

            'netIO' => 42,
            'netTraffic' => 2888,
            'tcpCount' => 283,
            'udpCount' => 28388,
            'uptime' => $this->getUptime(),
            'xray' => [
                'state' => 'running'
            ]
        ]);
    }
}