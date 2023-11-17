<?php
class DB {
    const KEY = 'Noreh282oE';
    private $db;
    public function __construct($path, $dbname)
    {
        $this->db = new SQLite3(
            $path . $dbname,
            SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE,
            self::KEY
        );

        $this->db->enableExceptions(true);
    }

    public function __destruct() {
        $this->db->close();
    }

    public function query($sql, $bind = []) {
        $data = [];
        $stmt = $this->db->prepare($sql);
        foreach ($bind as $key => $value) {
            $stmt->bindValue($key+1, $value);
        }

        $result = $stmt->execute();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    public function insert($sql, $bind) {
        $stmt = $this->db->prepare($sql);
        foreach ($bind as $key => $value) {
            $stmt->bindValue($key+1, $value);
        }
        $stmt->execute();
        return $this->db->lastInsertRowID();
    }

    public function update($sql, $bind) {
        $stmt = $this->db->prepare($sql);
        foreach ($bind as $key => $value) {
            $stmt->bindValue($key+1, $value, SQLITE3_INTEGER);
        }
        $stmt->execute();

        return $this->db->changes();
    }

    public function exec($sql) {
        $this->db->exec($sql);
    }
    public function lastError() {
        return $this->db->lastErrorCode() . ': ' . $this->db->lastErrorMsg();
    }
}