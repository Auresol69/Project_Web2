<!-- Cập nhật thông tin khách hàng -->
<?php
require_once '../connect_db.php'; // Gọi lớp kết nối

$db = new connect_db(); // Khởi tạo đối tượng kết nối

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id']; 
    $data = [
        'ho_ten' => $_POST['ho_ten'],
        'email' => $_POST['email'],
        'so_dien_thoai' => $_POST['phone']
    ];

    // Cập nhật dữ liệu
    $db->update("users", $data, $id);

    // Quay lại danh sách khách hàng
    header("Location: customer_listing.php");
    exit();
}
echo "Lỗi cập nhật thông tin khách hàng";
?>


