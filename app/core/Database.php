<?php
class Database {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public function query($sql) {
        return $this->conn->query($sql);
    }

    public function escape($data) {
        return $this->conn->real_escape_string($data);
    }
}
