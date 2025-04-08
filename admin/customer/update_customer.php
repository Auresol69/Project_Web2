<!-- Cập nhật thông tin khách hàng -->
<?php
require_once '../connect_db.php';
$db = new connect_db();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $ho_ten = $_POST['ho_ten'];
    $email = $_POST['email'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $trang_thai = $_POST['trang_thai'];
    // $vai_tro = $_POST['vai_tro'];
    $mat_khau = $_POST['mat_khau']; // Mật khẩu mới (nếu có)

    if (!empty($mat_khau)) {
        $mat_khau = password_hash($mat_khau, PASSWORD_DEFAULT);
    } else {
        // Nếu không có mật khẩu mới, giữ nguyên mật khẩu cũ
        $mat_khau = $db->getById("users", $id)['mat_khau'];
    }

    // Cập nhật thông tin khách hàng
    $db->update("users", [
        "ho_ten" => $ho_ten,
        "email" => $email,
        "so_dien_thoai" => $so_dien_thoai,
        "trang_thai" => $trang_thai,
        // "vai_tro" => $vai_tro,
        "mat_khau" => $mat_khau
    ], $id);

    // Chuyển hướng về danh sách khách hàng
    header("Location: ../index.php?page=customer");
    exit();
}
?>