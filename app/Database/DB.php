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

    static public function beginTransaction() {
        self::instance()->exec('BEGIN');
    }
    static public function commit() {
        self::instance()->exec('COMMIT');
    }

    static public function rollBack() {
        self::instance()->exec('ROLLBACK');
    }
}