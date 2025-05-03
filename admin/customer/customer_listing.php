<!-- Giao diện chính trang quản lý khách hàng -->
<?php
    require_once 'connect_db.php';
    $db = new connect_db();

    // Lấy dữ liệu tìm kiếm từ form
    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';

    // Xây dựng truy vấn tìm kiếm
    $sql = "SELECT * FROM customer WHERE 1=1";
    $params = [];

    if (!empty($id)) {
        $sql .= " AND macustomer = :id";
        $params['id'] = $id;
    }

    if (!empty($name)) {
        $sql .= " AND name LIKE :name";
        $params['name'] = "%$name%";
    }

    // Thực thi truy vấn
    $customers = $db->query($sql, $params)->fetchAll(PDO::FETCH_ASSOC);

    $powergroups = $db->query("SELECT * FROM powergroup")->fetchAll(PDO::FETCH_ASSOC);
?>


<link rel="stylesheet" href="customer/css/style.css?v=<?php echo time(); ?>">


<div class="main-content">
    <h1>Danh sách người dùng</h1>

    <div class="buttons">
        <a id="openAddModal" type="button" class="btn btn-primary btn-sm permission-them">Thêm người dùng</a>
    </div>

    <div class="listing-search">
        <form id="customer-search-form" onsubmit="return searchCustomers();">
            <fieldset>
                <legend>Tìm kiếm người dùng:</legend>
                ID: <input type="text" name="id" id="search-id">
                Tên người dùng: <input type="text" name="name" id="search-name">
                <input type="submit" value="Tìm">
                <input type="button" value="Xóa bộ lọc" onclick="clearFilters()">
            </fieldset>
        </form>
    </div>


    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Họ và Tên</th>
                <th>Email</th>
                <th>Nhóm quyền</th>
                <th>Số điện thoại</th>
                <th>Trạng thái</th>
                <th>Thao tác</th>
            </tr>
        </thead>

        <tbody id="customer-list">
            <!-- Customer rows will be loaded here by AJAX -->
        </tbody>
    </table>
    <div class="pagination" id="pagination">

    </div>
</div>

<!-- Modal chỉnh sửa -->
<div class="modal" id="editModal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Chỉnh sửa khách hàng</h3>
        <form id="editCustomerForm">
            <input type="hidden" id="edit-id" name="id">

            <label for="edit-name">Họ và Tên</label>
            <input type="text" id="edit-name" name="name" required>

            <label for="edit-email">Email</label>
            <input type="email" id="edit-email" name="email" required>

            <label for="edit-powergroupid">Nhóm quyền</label>
            <select id="edit-powergroupid" name="powergroupid" required>
                <?php foreach($powergroups as $powergroup) :?>
                <option value="<?=$powergroup['powergroupid'] ?>"><?=$powergroup['powergroupname']?></option>
                <?php endforeach; ?>
            </select>

            <label for="edit-phone">Số điện thoại</label>
            <input type="text" id="edit-phone" name="phone" required>

            <button type="submit" class="save-btn">Cập nhật</button>
        </form>
    </div>
</div>

<!-- Modal thêm khách hàng -->
<div id="addModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h3>Thêm khách hàng</h3>
        <form action="customer/add_customer.php" method="POST">
            <label for="name">Họ và Tên</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="powergroupid">Nhóm quyền</label>
            <select id="powergroupid" name="powergroupid" required>
                <?php foreach($powergroups as $powergroup) :?>
                <option value="<?=$powergroup['powergroupid'] ?>"><?=$powergroup['powergroupname']?></option>
                <?php endforeach; ?>
            </select>

            <label for="phone">Số điện thoại</label>
            <input type="text" id="phone" name="phone" required>

            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="save-btn">Thêm</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var currentPage = 1;
var currentFilters = {};

function searchCustomers() {
    currentFilters.id = $('#search-id').val();
    currentFilters.name = $('#search-name').val();
    currentPage = 1;
    loadCustomers(currentPage);
    return false;
}

function clearFilters() {
    $('#search-id').val('');
    $('#search-name').val('');
    currentFilters = {};
    currentPage = 1;
    loadCustomers(currentPage);
}

function loadCustomers(page) {
    currentPage = page;
    $.ajax({
        url: './customer/ajax.php?action=pagination',
        method: 'GET',
        data: {
            page: page,
            per_page: 5,
            id: currentFilters.id || '',
            name: currentFilters.name || ''
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                updateCustomerList(response.customers);
                updatePagination(page, response.totalPages);
                $('.total-items span').html(
                    `Có tất cả <strong>${response.totalRecordsCount}</strong> người dùng trên <strong>${response.totalPages}</strong> trang`
                );
            } else {
                alert('Không tìm thấy người dùng.');
                $('#customer-list').html('');
                $('#pagination').html('');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Lỗi tải danh sách người dùng:", textStatus, errorThrown, jqXHR.responseText);
            alert('Lỗi khi tải danh sách người dùng. Vui lòng kiểm tra console để biết chi tiết.');
        }
    });
}

function updateCustomerList(customers) {
    const customerList = $('#customer-list');
    customerList.empty();
    customers.forEach(customer => {
        const row = `
            <tr id="customer-${customer.macustomer}">
                <td>${customer.macustomer}</td>
                <td>${customer.name}</td>
                <td>${customer.powergroupid}</td
                <td>${customer.email}</td>
                <td>${customer.phone}</td>
                <td>
                    <button class="btn btn-warning btn-sm edit-btn permission-sua" data-id="${customer.macustomer}" data-name="${customer.name}" data-powergroupid="${customer.powergroupid}" data-email="${customer.email}" data-phone="${customer.phone}">Sửa</button>
                    <button class="btn btn-danger btn-sm delete-btn permission-xoa" data-id="${customer.macustomer}">Xóa</button>
                </td>
            </tr>
        `;
        customerList.append(row);
    });

    // Attach event listeners for edit and delete buttons
    $('.edit-btn').click(function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const email = $(this).data('email');
        const phone = $(this).data('phone');

        $('#edit-id').val(id);
        $('#edit-name').val(name);
        $('#edit-powergroupid').val(powergroupid);
        $('#edit-email').val(email);
        $('#edit-phone').val(phone);

        $('#editModal').show();
    });

    $('.delete-btn').click(function() {
        const id = $(this).data('id');
        if (confirm('Bạn có chắc muốn xóa người dùng này?')) {
            $.ajax({
                url: './customer/ajax.php?action=delete',
                method: 'GET',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        $('#customer-' + id).remove();
                        loadCustomers(currentPage);
                    } else {
                        alert('Xóa không thành công, vui lòng thử lại!');
                    }
                },
                error: function() {
                    alert('Đã xảy ra lỗi khi xóa người dùng.');
                }
            });
        }
    });
}

function updatePagination(currentPage, totalPages) {
    $('#pagination').html('');
    for (let i = 1; i <= totalPages; i++) {
        if (i === currentPage) {
            $('#pagination').append(`<strong>${i}</strong>`);
        } else {
            $('#pagination').append(`<a href="javascript:void(0);" onclick="loadCustomers(${i});">${i}</a>`);
        }
    }
}

$(document).ready(function() {
    loadCustomers(1);

    // Open add modal
    $('#openAddModal').click(function() {
        $('#addModal').show();
    });

    // Close modals
    $('.close-btn').click(function() {
        $(this).closest('.modal').hide();
    });

    // Close modal on outside click
    $(window).click(function(event) {
        if ($(event.target).hasClass('modal')) {
            $(event.target).hide();
        }
    });

    // Handle add customer form submission
    $('#addCustomerForm').submit(function(e) {
        e.preventDefault();
        const formData = {
            name: $('#add-name').val(),
            powergroupid: $('#add-powergroupid').val(),
            email: $('#add-email').val(),
            phone: $('#add-phone').val(),
            password: $('#add-password').val()
        };
        $.ajax({
            url: './customer/ajax.php?action=add',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert('Thêm người dùng thành công');
                    $('#addModal').hide();
                    loadCustomers(currentPage);
                } else {
                    alert('Thêm người dùng thất bại: ' + res.message);
                }
            },
            error: function() {
                alert('Lỗi khi thêm người dùng');
            }
        });
    });

    // Handle edit customer form submission
    $('#editCustomerForm').submit(function(e) {
        e.preventDefault();
        const formData = {
            id: $('#edit-id').val(),
            name: $('#edit-name').val(),
            powergroupid: $('#edit-powergroupid').val(),
            email: $('#edit-email').val(),
            phone: $('#edit-phone').val(),
            password: $('#edit-password').val()
        };
        $.ajax({
            url: './customer/ajax.php?action=edit',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(formData),
            dataType: 'json',
            success: function(res) {
                if (res.success) {
                    alert('Cập nhật người dùng thành công');
                    $('#editModal').hide();
                    loadCustomers(currentPage);
                } else {
                    alert('Cập nhật người dùng thất bại: ' + res.message);
                }
            },
            error: function() {
                alert('Lỗi khi cập nhật người dùng');
            }
        });
    });
});
</script>