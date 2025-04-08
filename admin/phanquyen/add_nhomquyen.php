<!-- Thêm khách hàng -->
<?php
require_once '../connect_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new connect_db();

    // Nhận dữ liệu từ form
    $powergroupname = trim($_POST['powergroupname']);

    // $vai_tro = trim($_POST['vai_tro']); // Lấy vai trò từ form

    // Kiểm tra email đã tồn tại chưa
    $existingPowerGroup = $db->query("SELECT powergroupid FROM powergroup WHERE powergroupname = ?", [$powergroupname])->fetch();
    if ($existingPowerGroup) {
        die("Nhóm quyền đã tồn tại! Vui lòng chọn nhóm quyền khác.");
    }

    // Chuẩn bị dữ liệu để thêm vào database
    $powerGroupData = [
        'powergroupname' => $powergroupname
    ];

    // Thêm khách hàng
    if ($db->insert("powergroup", $powerGroupData)) {
        // Chuyển hướng về danh sách khách hàng
        header("Location: ../index.php?page=phanquyen");
        exit();
    } else {
        echo "Lỗi khi thêm nhóm quyền.";
    }
}
?>