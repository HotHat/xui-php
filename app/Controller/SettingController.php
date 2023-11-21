<?php

namespace App\Controller;

use App\Database\DB;

class SettingController
{
    public function __construct()
    {
        checkLogin();
    }

    public function index() {
        render('xui/setting.php');
    }

    public function all() {
        respSuccess([
            "webListen" => "",
            "webPort" => 54321,
            "webCertFile" => "",
            "webKeyFile" => "",
            "webBasePath" => "/",
            "xrayTemplateConfig" => "{\n  \"api\": {\n    \"services\": [\n      \"HandlerService\",\n      \"LoggerService\",\n      \"StatsService\"\n    ],\n    \"tag\": \"api\"\n  },\n  \"inbounds\": [\n    {\n      \"listen\": \"127.0.0.1\",\n      \"port\": 62789,\n      \"protocol\": \"dokodemo-door\",\n      \"settings\": {\n        \"address\": \"127.0.0.1\"\n      },\n      \"tag\": \"api\"\n    }\n  ],\n  \"outbounds\": [\n    {\n      \"protocol\": \"freedom\",\n      \"settings\": {}\n    },\n    {\n      \"protocol\": \"blackhole\",\n      \"settings\": {},\n      \"tag\": \"blocked\"\n    }\n  ],\n  \"policy\": {\n    \"system\": {\n      \"statsInboundDownlink\": true,\n      \"statsInboundUplink\": true\n    }\n  },\n  \"routing\": {\n    \"rules\": [\n      {\n        \"inboundTag\": [\n          \"api\"\n        ],\n        \"outboundTag\": \"api\",\n        \"type\": \"field\"\n      },\n      {\n        \"ip\": [\n          \"geoip:private\"\n        ],\n        \"outboundTag\": \"blocked\",\n        \"type\": \"field\"\n      },\n      {\n        \"outboundTag\": \"blocked\",\n        \"protocol\": [\n          \"bittorrent\"\n        ],\n        \"type\": \"field\"\n      }\n    ]\n  },\n  \"stats\": {}\n}",
            "timeLocation" => "Asia/Shanghai"
        ]);
    }

    public function update() {
       respSuccess();
    }

    public function updateUser() {
        $s = $_POST;
        $user = DB::instance()->fetchOne('select * from user where username=?', [$s['oldUsername']]);
        if (empty($user)) { respFail('没有此用户'); }
        if (!hashVerify($s['oldPassword'], $user['password'])) { respFail('原密码错误'); }

        DB::instance()->update(
            'UPDATE user SET username=?, password=? WHERE username=?',
            [
                $s['newUsername'],
                hashMake($s['newPassword']),
                $s['oldUsername']
            ]
        );

        respSuccess();
    }
}