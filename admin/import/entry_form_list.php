<?php
require_once __DIR__ . '/../connect_db.php';

$db = new connect_db();

// Fetch all entry forms
$entryForms = $db->getAll('entry_form');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Phiếu Nhập</title>
    <link rel="stylesheet" href="../admin/css/entry_form_styles.css">
</head>
<body>
<div class="main-content">
    <h1>Danh sách Phiếu Nhập</h1>
    <p class="entry-form-button1"><a class="entry-form-button" href="import/entry_form.php">Tạo phiếu nhập mới</a></p>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Mã Phiếu Nhập</th>
                <th>Nhà Cung Cấp</th>
                <th>Nhân Viên</th>
                <th>Ngày Nhập</th>
                <th>Chiết Khấu (%)</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($entryForms as $entry): ?>
            <tr>
                <td><?= htmlspecialchars($entry['maphieunhap']) ?></td>
                <td><?= htmlspecialchars($entry['mancc']) ?></td>
                <td><?= htmlspecialchars($entry['mastaff']) ?></td>
                <td><?= htmlspecialchars($entry['ngaynhap']) ?></td>
                <td><?= isset($entry['loinhuan']) ? htmlspecialchars($entry['loinhuan']) : '0' ?></td>
                <td>
                    <a class="entry-form-button" href="import/entry_form.php?maphieunhap=<?= urlencode($entry['maphieunhap']) ?>">Chi tiết</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($entryForms)): ?>
            <tr>
                <td colspan="6">Chưa có phiếu nhập nào được tạo.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
</div>
</body>
</html>
