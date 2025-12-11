<?php
require_once '../app/config.php';

class Database {
    private $db_server = DB_SERVER;
    private $db_user = DB_USER;
    private $db_pass = DB_PASS;
    private $db_name = DB_NAME;
    private $conn;

    public function __construct() {
        try {
            $this->conn = mysqli_connect($this->db_server, $this->db_user, $this->db_pass, $this->db_name);
            if (!$this->conn) {
                throw new Exception(mysqli_connect_error());
            }
        } catch (Exception $e) {
            showError('Database connection failed: ' . $e->getMessage(), 500);
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}