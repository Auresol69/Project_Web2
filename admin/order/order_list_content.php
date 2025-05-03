<?php
require_once __DIR__ . '/../../admin/connect_db.php';

$db = new connect_db();

// Get filter parameters
$status = $_GET['status'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$city = $_GET['city'] ?? '';
$district = $_GET['district'] ?? '';

// Fetch orders with filters
$orders = $db->getOrdersWithFilters($status, $start_date, $end_date, $city, $district);
?>

<ul id="order-list">
    <li class="listing-item-heading">
        <div class="listing-prop">Mã đơn</div>
        <div class="listing-prop">Khách hàng</div>
        <div class="listing-prop">Ngày đặt</div>
        <div class="listing-prop">Tình trạng</div>
        <div class="listing-prop listing-button">Chi tiết</div>
        <div class="clear-both"></div>
    </li>

    <?php foreach ($orders as $order): ?>
    <li>
        <div class="listing-prop"><?= $order['maorder'] ?></div>
        <div class="listing-prop"><?= $order['customer_name'] ?></div>
        <div class="listing-prop"><?= date('d/m/Y', strtotime($order['created_at'])) ?></div>
        <div class="listing-prop">
            <span class="order-status 
                <?= $order['status'] == '0' ? 'status-pending' : '' ?>
                <?= $order['status'] == '1' ? 'status-confirmed' : '' ?>
                <?= $order['status'] == '2' ? 'status-completed' : '' ?>
                <?= $order['status'] == '3' ? 'status-cancelled' : '' ?>">
                <?= $db::getStatusText($order['status']) ?>
            </span>
        </div>
        <div class="listing-prop listing-button">
            <a href="../admin/order/order_details.php?id=<?= $order['maorder'] ?>">Chi tiết</a>
            <?php if ($order['status'] != '2' && $order['status'] != '3'): ?>
            <a href="javascript:void(0);" class="update-status permission-sua" data-order-id="<?= $order['maorder'] ?>"
                data-current-status="<?= $order['status'] ?>">
                Cập nhật
            </a>
            <?php endif; ?>
        </div>
        <div class="clear-both"></div>
    </li>
    <?php endforeach; ?>
</ul>