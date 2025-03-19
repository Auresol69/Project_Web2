<div class="main-content">
    <h1>Xóa sản phẩm</h1>
    <div id="content-box">
        <?php
        $error = false;
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            require 'connect_db.php'; // Đảm bảo đường dẫn đến file này là chính xác.

            $db = new Database(); // Tạo đối tượng Database
            $pdo = $db->conn; // Lấy kết nối PDO

            try {
                // Sử dụng câu lệnh prepared statement để xóa sản phẩm
                $stmt = $pdo->prepare("DELETE FROM `product` WHERE `id` = :id");
                $stmt->execute([':id' => $_GET['id']]);

                // Kiểm tra xem có bản ghi nào bị ảnh hưởng không
                if ($stmt->rowCount() === 0) {
                    $error = "Sản phẩm không tìm thấy hoặc đã được xóa.";
                }
            } catch (PDOException $e) {
                $error = "Không thể xóa sản phẩm. Lỗi: " . $e->getMessage();
            }
        ?>
            <div id="<?= $error ? 'error-notify' : 'success-notify' ?>" class="box-content">
                <h2><?= $error ? 'Thông báo' : 'Xóa sản phẩm thành công' ?></h2>
                <h4><?= $error ? $error : '' ?></h4>
                <a href="header.php?page=sanpham">Danh sách sản phẩm</a>
            </div>
        <?php
        }
        ?>
    </div>
</div>