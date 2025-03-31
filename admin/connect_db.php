<?php
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

        // Thêm thời gian tạo và thời gian cập nhật
        $data['created_time'] = date('Y-m-d H:i:s'); 
        $data['last_updated'] = date('Y-m-d H:i:s'); 

        $columns = implode(", ", array_keys($data));
        $values = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($values)";
        return $this->query($sql, $data);
    }

    // Cập nhật dữ liệu
    public function update($table, $data, $id) {

        // Thêm thời gian cập nhật
        $data['last_updated'] = date('Y-m-d H:i:s');  

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

    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }

    // Tìm kiếm dữ liệu
    public function search($table, $id = null, $name = null) {
        $sql = "SELECT * FROM $table WHERE 1"; // Luôn đúng để nối điều kiện
    
        $params = [];
    
        if (!empty($id)) {
            $sql .= " AND id = :id";
            $params['id'] = $id;
        }
    
        if (!empty($name)) {
            $sql .= " AND ho_ten LIKE :name"; // Nếu bảng sản phẩm, đổi "ho_ten" thành "ten_san_pham"
            $params['name'] = "%$name%";
        }
    
        return $this->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
    }
    
}

?>
