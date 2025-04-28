<!-- Thêm khách hàng -->
<?php
require_once '../connect_db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new connect_db();

    // Nhận dữ liệu từ form
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $powergroupid = trim($_POST['powergroupid']);
    $so_dien_thoai = trim($_POST['so_dien_thoai']);
    $mat_khau = password_hash($_POST['mat_khau'], PASSWORD_DEFAULT);
    $trang_thai = 'Hoạt động';
    // $vai_tro = trim($_POST['vai_tro']); // Lấy vai trò từ form

    // Kiểm tra email đã tồn tại chưa
    $existingCustomer = $db->query("SELECT macustomer FROM customer WHERE email = ?", [$email])->fetch();
    if ($existingCustomer) {
        die("Email đã tồn tại! Vui lòng chọn email khác.");
    }

    // Chuẩn bị dữ liệu để thêm vào database
    $customerData = [
        'name' => $name,
        'email' => $email,
        'powergroupid' => $powergroupid,
        'so_dien_thoai' => $so_dien_thoai,
        'mat_khau' => $mat_khau,
        'trang_thai' => $trang_thai
        // 'vai_tro' => $vai_tro
    ];

    // Thêm khách hàng
    if ($db->insert("customer", $customerData)) {
        // Chuyển hướng về danh sách khách hàng
        header("Location: ../index.php?page=customer");
        exit();
    } else {
        echo "Lỗi khi thêm khách hàng.";
    }
}
?>