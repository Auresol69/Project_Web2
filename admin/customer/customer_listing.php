<!-- Giao diện chính trang quản lý khách hàng -->
<?php
    require_once 'connect_db.php';
    $db = new connect_db();

    // Lấy dữ liệu tìm kiếm từ form
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';

    // Xây dựng truy vấn tìm kiếm
    $sql = "SELECT * FROM users WHERE 1=1";
    $params = [];

    if (!empty($id)) {
        $sql .= " AND id = :id";
        $params['id'] = $id;
    }

    if (!empty($name)) {
        $sql .= " AND ho_ten LIKE :name";
        $params['name'] = "%$name%";
    }

    // Thực thi truy vấn
    $customers = $db->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
?>


<link rel="stylesheet" href="customer/css/style.css?v=<?php echo time(); ?>">


<div class="main-content">
    <h1>Danh sách người dùng</h1>

    <div class="buttons">
        <a id="openAddModal">Thêm người dùng</a>
    </div>

    <div class="listing-search">
        <form method="POST">
            <fieldset>
                <legend>Tìm kiếm người dùng:</legend>
                ID: <input type="text" name="id">
                Tên người dùng: <input type="text" name="name">
                <input type="submit" value="Tìm">
            </fieldset>
        </form>
    </div>


    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ và Tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Trạng thái</th>
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
                    <td><?= $customer['trang_thai']; ?></td>
                    <td>
                        <!-- Nút "Sửa" có thêm class edit-btn và data attributes -->
                        <a href="#" class="btn btn-warning btn-sm edit-btn"
                            data-id="<?= $customer['id']; ?>"
                            data-name="<?= $customer['ho_ten']; ?>"
                            data-email="<?= $customer['email']; ?>"
                            data-phone="<?= $customer['so_dien_thoai']; ?>"
                            data-vaitro="<?= $customer['vai_tro']; ?>"
                            data-trangthai="<?= $customer['trang_thai']; ?>">
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

            <label for="edit-vaitro">Vai trò</label>
            <select id="edit-vaitro" name="vai_tro" required>
                <option value="Khách hàng">Khách hàng</option>
                <option value="Nhân viên">Nhân viên</option>
                <option value="Admin">Admin</option>
            </select>

            <label for="edit-trangthai">Trạng thái</label>
            <select id="edit-trangthai" name="trang_thai" required>
                <option value="Hoạt động">Hoạt động</option>
                <option value="Bị khóa">Bị khóa</option>
            </select>

            <label for="edit-password">Mật khẩu mới</label>
            <input type="password" id="edit-password" name="mat_khau" placeholder="Để trống nếu không đổi mật khẩu">

            <button type="submit" class="save-btn">Cập nhật</button>
        </form>
    </div>
</div>

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
                <option value="Nhân viên">Nhân viên</option>
                <option value="Admin">Admin</option>
            </select>

            <label for="trang_thai">Trạng thái</label>
            <select id="trang_thai" name="trang_thai" required>
                <option value="Hoạt động">Hoạt động</option>
                <option value="Bị khóa">Bị khóa</option>
            </select>

            <button type="submit" class="save-btn">Thêm</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    // Lấy các modal
    let addModal = document.getElementById("addModal");
    let editModal = document.getElementById("editModal");

    // Lấy các nút mở modal
    let openAddModalBtn = document.getElementById("openAddModal");
    let editButtons = document.querySelectorAll(".edit-btn");

    // Lấy tất cả các nút đóng modal
    let closeButtons = document.querySelectorAll(".close-btn");

    // Đảm bảo modal luôn ẩn khi tải lại trang
    addModal.style.display = "none";
    editModal.style.display = "none";

    // Mở modal thêm khách hàng
    openAddModalBtn.addEventListener("click", function () {
        addModal.style.display = "block";
    });

    // Đóng modal khi nhấn nút "X"
    closeButtons.forEach(button => {
        button.addEventListener("click", function () {
            this.closest(".modal").style.display = "none";
        });
    });

    // Mở modal chỉnh sửa khi nhấn vào nút "Sửa"
    editButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault(); // Ngăn chuyển trang

            // Lấy dữ liệu từ data-attributes của nút
            document.getElementById("edit-id").value = this.dataset.id;
            document.getElementById("edit-name").value = this.dataset.name;
            document.getElementById("edit-email").value = this.dataset.email;
            document.getElementById("edit-phone").value = this.dataset.phone;
            document.getElementById("edit-vaitro").value = this.dataset.vaitro;
            document.getElementById("edit-trangthai").value = this.dataset.trangthai;

            // Hiện modal chỉnh sửa
            editModal.style.display = "block";
        });
    });

    // Đóng modal khi bấm ra ngoài
    window.addEventListener("click", function (event) {
        if (event.target.classList.contains("modal")) {
            event.target.style.display = "none";
        }
    });
});

</script>