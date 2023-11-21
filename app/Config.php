<?php declare(strict_types=1);

namespace App;

enum Config: string
{
    case V2RAY_PATH = '/usr/local/bin/v2ray';
    case V2RAY_CONFIG_DIR = '/usr/local/etc/v2ray/';
    case V2RAY_PORT = '12385';
    case ADMIN_PASSWORD='TeTzb23@#*';
}
