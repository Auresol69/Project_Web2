<!-- Giao diện chính trang quản lý khách hàng -->
<?php
require_once "Customer.php";

$customerObj = new Customer();
$customers = $customerObj->getAllCustomers();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="customer/css/style.css">
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ và Tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Thao tác</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($customers as $customer) : ?>
                <tr>
                    <td><?= $customer['id']; ?></td>
                    <td><?= $customer['ho_ten']; ?></td>
                    <td><?= $customer['email']; ?></td>
                    <td><?= $customer['so_dien_thoai']; ?></td>
                    <td>
                        <a href="edit_customer.php?id=<?= $customer['id']; ?>" class="btn btn-warning btn-sm">Sửa</a>
                        <a href="delete_customer.php?id=<?= $customer['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa không?');">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
