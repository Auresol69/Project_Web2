<?php
require_once __DIR__ . '/../connect_db.php';

$db = new connect_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maphieunhap = $_GET['maphieunhap'] ?? '';
    $masp = $_POST['masp'] ?? '';
    $dongianhap = intval($_POST['dongianhap'] ?? 0);
    $soluongnhap = intval($_POST['soluongnhap'] ?? 0);

    if ($maphieunhap && $masp && $dongianhap > 0 && $soluongnhap > 0) {
        // Insert into detail_entry_form
        $data = [
            'maphieunhap' => $maphieunhap,
            'masp' => $masp,
            'dongianhap' => $dongianhap,
            'soluongnhap' => $soluongnhap
        ];
        $result = $db->insert('detail_entry_form', $data);

        if ($result) {
            header("Location: entry_form.php?maphieunhap=" . urlencode($maphieunhap));
            exit;
        } else {
            $error = "Lỗi khi thêm sản phẩm vào phiếu nhập.";
        }
    } else {
        $error = "Dữ liệu không hợp lệ.";
    }
} else {
    $error = "Phương thức không hợp lệ.";
}

if (isset($error)) {
    echo "<p style='color:red;'>$error</p>";
    echo "<p><a href='entry_form.php?maphieunhap=" . htmlspecialchars($maphieunhap) . "'>Quay lại</a></p>";
}
?>
