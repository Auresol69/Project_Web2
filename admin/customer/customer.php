<?php
require_once "connect_db.php";

class customer {
    private $conn;

    public function __construct() {
        $db = new connect_db();
        // $this->conn = $db->getConnection();
    }

    // Lấy danh sách tất cả khách hàng
    public function getAllCustomers() {
        $sql = "SELECT * FROM users ORDER BY id DESC";  
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(); 
    }
}
?>