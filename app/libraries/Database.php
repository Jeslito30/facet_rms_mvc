<?php
class Database {
    private $db_server = "localhost";
    private $db_user = "wb_user";
    private $db_pass = "1234";
    private $db_name = "facet_rms";
    private $conn;

    public function __construct() {
        try {
            $this->conn = mysqli_connect($this->db_server, $this->db_user, $this->db_pass, $this->db_name);
            if (!$this->conn) {
                throw new Exception(mysqli_connect_error());
            }
        } catch (Exception $e) {
            // If connection fails, return a JSON error message
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Database connection failed: ' . $e->getMessage()
            ]);
            exit();
        }
    }

    public function getConnection() {
        return $this->conn;
    }
}