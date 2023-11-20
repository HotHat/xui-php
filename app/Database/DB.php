<?php
namespace App\Database;

class DB
{
    private static $instance;
    static public function instance() {
        if (!self::$instance) {
            self::$instance = new SqliteDB(__DIR__ . '/', 'xui.db');
        }

        return self::$instance;
    }
}