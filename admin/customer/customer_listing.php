<!-- Giao diện chính trang quản lý khách hàng -->
<?php
require_once 'connect_db.php';
$db = new connect_db();
$customers = $db->getAll("users");
?>

<link rel="stylesheet" href="customer/css/style.css?v=<?php echo time(); ?>">


<div class="main-content">
    <h1>Danh sách người dùng</h1>
    <div class="buttons">
        <a id="openAddModal">Thêm người dùng</a>
    </div>

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
</div>

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

        modal.style.display = "none";

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

<!-- Modal thêm khách hàng -->
<div id="addModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Thêm khách hàng</h3>
        <form action="customer/add_customer.php" method="POST">
            <label for="ho_ten">Họ và Tên</label>
            <input type="text" id="ho_ten" name="ho_ten" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="so_dien_thoai">Số điện thoại</label>
            <input type="text" id="so_dien_thoai" name="so_dien_thoai" required>

            <label for="mat_khau">Mật khẩu</label>
            <input type="password" id="mat_khau" name="mat_khau" required>

            <label for="vai_tro">Vai trò</label>
            <select id="vai_tro" name="vai_tro" required>
                <option value="Khách hàng">Khách hàng</option>
                <option value="Quản trị viên">Quản trị viên</option>
                <option value="Nhân viên">Nhân viên</option>
            </select>

            <button type="submit" class="save-btn">Thêm</button>
        </form>
    </div>
</div>

<script>
    document.getElementById("openAddModal").addEventListener("click", function () {
        document.getElementById("addModal").style.display = "block";
    });

    document.querySelector(".close-btn").addEventListener("click", function () {
        document.getElementById("addModal").style.display = "none";
    });

    window.onclick = function (event) {
        if (event.target == document.getElementById("addModal")) {
            document.getElementById("addModal").style.display = "none";
        }
    };
</script>
