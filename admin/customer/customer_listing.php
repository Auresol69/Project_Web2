<!-- Giao diện chính trang quản lý khách hàng -->
<?php
require_once 'connect_db.php';
$db = new connect_db();
$customers = $db->getAll("users");
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
                        <!-- Nút "Sửa" có thêm class edit-btn và data attributes -->
                        <a href="#" class="btn btn-warning btn-sm edit-btn"
                                data-id="<?= $customer['id']; ?>"
                                data-name="<?= $customer['ho_ten']; ?>"
                                data-email="<?= $customer['email']; ?>"
                                data-phone="<?= $customer['so_dien_thoai']; ?>">
                            Sửa
                        </a>

                        <a href="customer/delete_customer.php?id=<?= $customer['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa không?');">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<!-- Modal chỉnh sửa -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Chỉnh sửa khách hàng</h3>
        <form action="customer/update_customer.php" method="POST">
            <input type="hidden" id="edit-id" name="id">

            <label for="edit-name">Họ và Tên</label>
            <input type="text" id="edit-name" name="ho_ten" required>

            <label for="edit-email">Email</label>
            <input type="email" id="edit-email" name="email" required>

            <label for="edit-phone">Số điện thoại</label>
            <input type="text" id="edit-phone" name="so_dien_thoai" required>

            <button type="submit" class="save-btn">Cập nhật</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const modal = document.getElementById("editModal");
        const closeBtn = document.querySelector(".close-btn");
        const editButtons = document.querySelectorAll(".edit-btn");

        // Duyệt qua từng nút "Sửa" và gán sự kiện click
        editButtons.forEach(button => {
            button.addEventListener("click", function (event) {
                event.preventDefault(); // Ngăn chuyển trang do thẻ <a>

                // Lấy dữ liệu từ data-attributes của nút
                document.getElementById("edit-id").value = this.dataset.id;
                document.getElementById("edit-name").value = this.dataset.name;
                document.getElementById("edit-email").value = this.dataset.email;
                document.getElementById("edit-phone").value = this.dataset.phone;

                // Hiện modal lên
                modal.style.display = "block";
            });
        });

        // Đóng modal khi bấm nút "X"
        closeBtn.addEventListener("click", function () {
            modal.style.display = "none";
        });

        // Đóng modal khi bấm ra ngoài
        window.addEventListener("click", function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });
    });

</script>

</body>


</html>