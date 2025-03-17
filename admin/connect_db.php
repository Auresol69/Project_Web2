<?php
class connect_db {
    private $host = "localhost";
    private $db_name = "treeshop";
    private $username = "root";
    private $password = ""; 
    public $conn; //sử dụng cho PDO

    // Get the PDO database connection
    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);

        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }

    // Close the PDO database connection
    public function closeConnection() {
        $this->conn = null;
    }

}

?>