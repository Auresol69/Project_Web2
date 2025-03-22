<div class="main-content">
    <h1>Xóa sản phẩm</h1>
    <div id="content-box">
        <?php
        $error = false;
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            require '../connect_db.php'; // Đảm bảo đường dẫn đến file này là chính xác.

            $db = new connect_db(); // Tạo đối tượng connect_db

            try {
                // Sử dụng phương thức delete của lớp connect_db để xóa sản phẩm
                $result = $db->delete('product', $_GET['id']);

                // Kiểm tra xem có bản ghi nào bị ảnh hưởng không
                if ($result->rowCount() === 0) {
                    $error = "Sản phẩm không tìm thấy hoặc đã được xóa.";
                }
            } catch (PDOException $e) {
                $error = "Không thể xóa sản phẩm. Lỗi: " . $e->getMessage();
            }
        ?>
            <div id="<?= $error ? 'error-notify' : 'success-notify' ?>" class="box-content">
                <h2><?= $error ? 'Thông báo' : 'Xóa sản phẩm thành công' ?></h2>
                <h4><?= $error ? $error : '' ?></h4>
                <a href="../header.php?page=sanpham">Danh sách sản phẩm</a>
            </div>
        <?php
        }
        ?>
    </div>
</div>