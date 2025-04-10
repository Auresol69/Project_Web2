<?php
    require_once 'connect_db.php';
    $db = new connect_db();

    // Lấy các powergroup và chức năng tương ứng
    $sql = "SELECT pg.*, pf.funcid 
            FROM powergroup pg 
            LEFT JOIN powergroup_func pf on pg.powergroupid=pf.powergroupid
            WHERE 1=1";
    $params = [];

    $sql.= " AND pg.status != 0"; // Xóa rồi thì không hiển thị

    $powergroups_raw= $db->query($sql,$params)->fetchAll(PDO::FETCH_ASSOC);

    $powergroups = [];

    foreach($powergroups_raw as $row){
        $id = $row['powergroupid']; // vì có thể nhiều dòng trùng powergroupid
        if (!isset($powergroups[$id])){
            $powergroups[$id] = [
                'powergroupid' => $row['powergroupid'],
                'powergroupname' => $row['powergroupname'],
                'status' => $row['status'],
                'created_time' => $row['created_time'],
                'last_updated' => $row['last_updated'],
                'funcs' => []
            ];
        }
        if (!empty($row['funcid']))
        {
            $powergroups[$id]['funcs'][]=$row['funcid'];
        }
    }

    // Lấy các chức năng
    $sql = "SELECT * FROM func WHERE 1=1";

    $funcs = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<link rel="stylesheet" href="customer/css/style.css?v=<?php echo time(); ?>">

<div class="main-content">
    <h1>Phân quyền người dùng</h1>
    <div class="buttons">
        <a id="openAddModal">Thêm nhóm quyền</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên nhóm quyền</th>
                <th>trạng thái</th>
                <th>Thời gian tạo</th>
                <th>Update gần đây nhất</th>
                <th>Thao tác</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($powergroups as $powergroup) : ?>
            <tr>
                <td><?= $powergroup['powergroupid']; ?></td>
                <td><?= $powergroup['powergroupname']; ?></td>
                <td><?= ($powergroup['status'] == 1)? "Hoạt động":"Tạm dừng"; ?></td>
                <td><?= $powergroup['created_time']; ?></td>
                <td><?= $powergroup['last_updated']; ?></td>
                <td>
                    <a href="#" class="btn btn-warning btn-sm edit-btn"
                        data-powergroupid="<?= $powergroup['powergroupid']; ?>"
                        data-powergroupname="<?= $powergroup['powergroupname']; ?>"
                        data-funcs="<?= htmlspecialchars(json_encode($powergroup['funcs']));?>">
                        Sửa
                    </a>

                    <a href="phanquyen/delete_phanquyen.php?id=<?= $powergroup['powergroupid']; ?>"
                        class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa không?');">Xóa</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal thêm nhóm quyền -->
<div class="modal" id="addModal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Thêm nhóm quyền</h3>
        <form action="phanquyen/add_nhomquyen.php" method="POST">
            <label for="powergroupname">Tên nhóm quyền</label>
            <input type="text" id="powergroupname" name="powergroupname" required>

            <button type="submit" class="save-btn">Thêm</button>
        </form>
    </div>
</div>

<!-- Modal chỉnh sửa -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Chỉnh sửa Nhóm quyền</h3>
        <form action="phanquyen/update_nhomquyen.php" method="POST">
            <input type="hidden" id="edit-id" name="powergroupid">

            <label for="edit-name">Tên nhóm quyền</label>
            <input type="text" id="edit-name" name="powergroupname">

            <table>
                <thead>
                    <tr>
                        <?php foreach($funcs as $func) :?>
                        <th><?=htmlspecialchars(string: $func['funcname']) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php foreach($funcs as $func) :?>
                        <td>
                            <input type="checkbox" name="permissions[]" value="<?= $func['funcid'] ?>">
                        </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="save-btn">Cập nhật</button>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    let addModal = document.getElementById("addModal");
    let editModal = document.getElementById("editModal");

    addModal.style.display = "none";
    editModal.style.display = "none";


    let openAddModalBtn = document.getElementById("openAddModal");
    let editButtons = document.querySelectorAll(".edit-btn");

    let closeButtons = document.querySelectorAll(".close-btn");

    openAddModalBtn.addEventListener("click", function() {
        addModal.style.display = "block";
    });

    closeButtons.forEach(button => {
        button.addEventListener("click", function() {
            this.closest(".modal").style.display = "none";
        });
    });

    editButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            event.preventDefault();


            document.getElementById("edit-id").value = this.dataset.powergroupid;

            document.getElementById("edit-name").value = this.dataset.powergroupname;

            editModal.style.display = "block";

            // Tự động check những checkbox tương ứng với chức năng của powergroup đã có
            let funcsJSON = this.dataset.funcs;

            let funcs = JSON.parse(funcsJSON);

            funcs.forEach(id => {
                let checkbox = document.querySelector(
                    `input[type="checkbox"][value="${id}"]`);
                if (checkbox)
                    checkbox.checked = true;
            });
        });
    });

    window.addEventListener("click", function(event) {
        if (event.target.classList.contains("modal")) {
            event.target.style.display = "none";
        }
    });
});
</script>