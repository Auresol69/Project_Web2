<!-- Cập nhật thông tin khách hàng -->
<?php
require_once '../connect_db.php';
$db = new connect_db();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $ho_ten = $_POST['ho_ten'];
    $email = $_POST['email'];
    $so_dien_thoai = $_POST['so_dien_thoai'];

    // Cập nhật thông tin khách hàng
    $db->update("users", [
        "ho_ten" => $ho_ten,
        "email" => $email,
        "so_dien_thoai" => $so_dien_thoai
    ], $id);

    // Chuyển hướng về danh sách khách hàng
    header("Location: ../header.php?page=customer");
    exit();
}
?>