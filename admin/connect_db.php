<?php
// class connect_db {
//     private $host = "localhost";
//     private $db_name = "treeshop";
//     private $username = "root";
//     private $password = ""; 
//     public $conn; //sử dụng cho PDO

//     // Get the PDO database connection
//     public function getConnection() {
//         $this->conn = null;

//         try {
//             $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);

//         } catch(PDOException $exception) {
//             echo "Connection error: " . $exception->getMessage();
//         }
//         return $this->conn;
//     }
//     // Close the PDO database connection
//     public function closeConnection() {
//         $this->conn = null;
//     }
// }


class connect_db {
    private $host = "localhost";
    private $db_name = "treeshop";
    private $username = "root";
    private $password = "";
    private $conn;

    // Kết nối CSDL
    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            die("Lỗi kết nối: " . $exception->getMessage());
        }
    }

    // Hàm chạy truy vấn (SELECT, INSERT, UPDATE, DELETE)
    public function query($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Lấy danh sách (SELECT * FROM)
    public function getAll($table) {
        $sql = "SELECT * FROM $table";
        return $this->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy 1 bản ghi theo ID
    public function getById($table, $id) {
        $sql = "SELECT * FROM $table WHERE id = :id";
        return $this->query($sql, ['id' => $id])->fetch(PDO::FETCH_ASSOC);
    }

    // Thêm dữ liệu
    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        return $this->query($sql, $data);
    }

    // Cập nhật dữ liệu
    public function update($table, $data, $id) {
        $setValues = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
        $sql = "UPDATE $table SET $setValues WHERE id = :id";
        $data['id'] = $id;
        return $this->query($sql, $data);
    }

    // Xóa dữ liệu
    public function delete($table, $id) {
        $sql = "DELETE FROM $table WHERE id = :id";
        return $this->query($sql, ['id' => $id]);
    }
}

?>