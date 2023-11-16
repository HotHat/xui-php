<?php

namespace App\Controller;

class DashboardController
{
    public function __construct()
    {
        check_login();
    }

    public function dashboard() {

        render('xui/index.php');
    }

    public function status() {
        resp_success([
            'cpu' => 23,
            'disk' => [
                'current' => 20,
                'total' => 32
            ],
            'loads' => [2.23, 20.20, 283.],
            'mem' => [
                'current' => 58,
                'total' => 60
            ],
            'netIO' => 42,
            'netTraffic' => 2888,
            'swap' => [
                'current' => 1024 * 100,
                'total' => 1024*1024
            ],
            'tcpCount' => 283,
            'udpCount' => 28388,
            'uptime' => 8882,
            'xray' => [
                'state' => 'running'
            ]
        ]);
    }
}