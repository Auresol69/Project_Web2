<?php
session_start();
include 'connect_db.php';
include 'phantrang.php'; // Đảm bảo bạn đã include class Pagination

$config_name = "product";
$config_title = "sản phẩm";

// Kết nối cơ sở dữ liệu
$db = new Database();
$conn = $db->conn;

// Xử lý tìm kiếm
if (!empty($_GET['action']) && $_GET['action'] == 'search' && !empty($_POST)) {
    $_SESSION[$config_name . '_filter'] = $_POST;
    header('Location: ' . $config_name . '_listing.php');
    exit;
}

// Xây dựng điều kiện WHERE
$where = "";
$params = [];
if (!empty($_SESSION[$config_name . '_filter'])) {
    foreach ($_SESSION[$config_name . '_filter'] as $field => $value) {
        if (!empty($value)) {
            switch ($field) {
                case 'name':
                    $where .= (!empty($where)) ? " AND `" . $field . "` LIKE ?" : "`" . $field . "` LIKE ?";
                    $params[] = "%$value%";
                    break;
                default:
                    $where .= (!empty($where)) ? " AND `" . $field . "` = ?" : "`" . $field . "` = ?";
                    $params[] = $value;
                    break;
            }
        }
    }
}

// Thiết lập phân trang
$item_per_page = (!empty($_GET['per_page'])) ? (int)$_GET['per_page'] : 15;
$current_page = (!empty($_GET['page'])) ? (int)$_GET['page'] : 1;

// Đảm bảo current_page không âm
if ($current_page < 1) {
    $current_page = 1;
}

// Tính toán offset
$offset = ($current_page - 1) * $item_per_page;
if ($offset < 0) {
    $offset = 0; // Đảm bảo offset không âm
}

// Truy vấn tổng số bản ghi
$totalQuery = "SELECT COUNT(*) FROM `product`" . (!empty($where) ? " WHERE " . $where : "");
$totalStmt = $conn->prepare($totalQuery);
$totalStmt->execute($params);
$totalRecordsCount = $totalStmt->fetchColumn();

// Khởi tạo đối tượng phân trang
$pagination = new Pagination($current_page, $totalRecordsCount, $item_per_page);

// Truy vấn sản phẩm
$productQuery = "SELECT * FROM `product`" . (!empty($where) ? " WHERE " . $where : "") . " ORDER BY `id` DESC LIMIT $offset, $item_per_page";
$productStmt = $conn->prepare($productQuery);

// Thực thi với các tham số
$productStmt->execute($params);
$products = $productStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HTML và CSS cho trang -->
<style>
        body, h1, p, ul {
        margin: 0;
        padding: 0;
    }

    /* Thiết lập phông chữ và màu nền cho toàn bộ trang */
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
    }

    /* Kiểu cho tiêu đề chính */
    .main-content h1 {
        text-align: center;
        margin: 20px 0;
        color: #2c3e50;
    }

    /* Kiểu cho các mục trong danh sách */
    .listing-items {
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* Kiểu cho các nút bấm */
    .buttons {
        text-align: right;
        margin-bottom: 20px;
    }

    .buttons a {
        background: #006ADD;
        color: white;
        padding: 10px 15px;
        text-decoration: none;
        border-radius: 5px;
    }

    .buttons a:hover {
        background: #2980b9;
    }

    /* Kiểu cho phần tìm kiếm */
    .listing-search {
        margin-bottom: 20px;
    }

    .listing-search input[type="text"] {
        padding: 8px;
        margin-right: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    /* Kiểu cho danh sách sản phẩm */
    ul {
        list-style-type: none;
    }

    .listing-item-heading {
        background: #eaeaea;
        font-weight: bold;
        padding: 10px;
    }

    .listing-prop {
        display: inline-block;
        width: 14%;
        padding: 10px;
        text-align: center;
    }

    .listing-prop img {
        max-width: 100px;
        max-height: 100px;
        border-radius: 5px;
    }

    .listing-button a {
        display: inline-block;
        background: #e74c3c;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        text-decoration: none;
    }

    .listing-button a:hover {
        background: #c0392b;
    }

    /* Kiểu cho thông tin ngày */
    .listing-time {
        color: #7f8c8d;
    }

    /* Clearfix cho các phần tử */
    .clear-both {
        clear: both;
    }

    /* Kiểu cho thông tin tổng số sản phẩm */
    .total-items {
        text-align: center;
        margin: 20px 0;
        font-style: italic;
    }

        /* Kiểu cho phân trang */
    .pagination {
        text-align: center;
        margin-top: 20px;
    }

    .pagination a {
        display: inline-block;
        padding: 8px 12px;
        margin: 0 5px;
        background: #006ADD;
        color: white;
        border-radius: 4px;
        text-decoration: none;
    }

    .pagination a:hover {
        background: #2980b9;
    }
</style>
<div class="main-content">
    <h1>Danh sách <?= $config_title ?></h1>
    <div class="listing-items">
        <div class="buttons">
            <a href="./<?= $config_name ?>_edit.php">Thêm <?= $config_title ?></a>
        </div>
        <div class="listing-search">
            <form id="<?= $config_name ?>-search-form" action="<?= $config_name ?>_listing.php?action=search" method="POST">
                <fieldset>
                    <legend>Tìm kiếm <?= $config_title ?>:</legend>
                    ID: <input type="text" name="id" value="<?= !empty($id) ? htmlspecialchars($id) : "" ?>" />
                    Tên <?= $config_title ?>: <input type="text" name="name" value="<?= !empty($name) ? htmlspecialchars($name) : "" ?>" />
                    <input type="submit" value="Tìm" />
                </fieldset>
            </form>
        </div>
        <div class="total-items">
            <span>Có tất cả <strong><?= $totalRecordsCount ?></strong> <?= $config_title ?> trên <strong><?= $pagination->total_pages ?></strong> trang</span>
        </div>
        <ul>
            <li class="listing-item-heading">
                <div class="listing-prop listing-img">Ảnh</div>
                <div class="listing-prop listing-name">Tên <?= $config_title ?></div>
                <div class="listing-prop listing-button">Xóa</div>
                <div class="listing-prop listing-button">Sửa</div>
                <div class="listing-prop listing-button">Copy</div>
                <div class="listing-prop listing-time">Ngày tạo</div>
                <div class="listing-prop listing-time">Ngày cập nhật</div>
                <div class="clear-both"></div>
            </li>
            <?php foreach ($products as $row) { ?>
                <li>
                    <div class="listing-prop listing-img"><img src="../<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" title="<?= htmlspecialchars($row['name']) ?>" /></div>
                    <div class="listing-prop listing-name"><?= htmlspecialchars($row['name']) ?></div>
                    <div class="listing-prop listing-button">
                        <a href="./<?= $config_name ?>_del.php?id=<?= $row['id'] ?>">Xóa</a>
                    </div>
                    <div class="listing-prop listing-button">
                        <a href="./<?= $config_name ?>_edit.php?id=<?= $row['id'] ?>">Sửa</a>
                    </div>
                    <div class="listing-prop listing-button">
                        <a href="./<?= $config_name ?>_editing.php?id=<?= $row['id'] ?>&task=copy">Copy</a>
                    </div>
                    <div class="listing-prop listing-time"><?= date('d/m/Y H:i', strtotime($row['created_time'])) ?></div>
                    <div class="listing-prop listing-time"><?= date('d/m/Y H:i', strtotime($row['last_updated'])) ?></div>
                    <div class="clear-both"></div>
                </li>
            <?php } ?>
        </ul>
        <?php if($pagination->total_pages > 1){ ?>
            <div class="pagination"><?= $pagination->render(); ?> </div>
        <?php } ?>
        <div class="clear-both"></div>
    </div>
</div>

