<?php

namespace App\Controller;

class InboundController
{
    public function __construct()
    {
        checkLogin();
    }

    public function index() {
        render('xui/inbounds.php');
    }

    public function list() {
        respSuccess([
            [
                "id" => 1,
                "up" => 0,
                "down" => 0,
                "total" => 12884901888,
                "remark" => "abc-user-add",
                "enable" => true,
                "expiryTime" => 1701321883788,
                "listen" => "127.0.0.0.1",
                "port" => 45643,
                "protocol" => "vmess",
                "settings" => "{\n  \"clients\": [\n    {\n      \"id\": \"a26ca352-70e2-4149-b049-b4adf10a28ff\",\n      \"alterId\": 0\n    }\n  ],\n  \"disableInsecureEncryption\": false\n}",
                "streamSettings" => "{\n  \"network\": \"tcp\",\n  \"security\": \"none\",\n  \"tcpSettings\": {\n    \"header\": {\n      \"type\": \"http\",\n      \"request\": {\n        \"method\": \"GET\",\n        \"path\": [\n          \"/\"\n        ],\n        \"headers\": {}\n      },\n      \"response\": {\n        \"version\": \"1.1\",\n        \"status\": \"200\",\n        \"reason\": \"OK\",\n        \"headers\": {}\n      }\n    }\n  }\n}",
                "tag" => "inbound-45643",
                "sniffing" => "{\n  \"enabled\": true,\n  \"destOverride\": [\n    \"http\",\n    \"tls\"\n  ]\n}"
            ]
        ]);
    }

}