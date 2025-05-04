<?php
require_once '../../admin/connect_db.php';

$db = new connect_db();

$order_id = $_GET['id'] ?? '';
if (empty($order_id)) {
    header('Location: order_list.php');
    exit;
}

$order = $db->getOrderDetails($order_id);
if (!$order) {
    header('Location: order_list.php');
    exit;
}
?>

<div class="container mt-4">
    <h2>Chi tiết Đơn hàng #<?= $order['maorder'] ?></h2>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">Thông tin đơn hàng</div>
                <div class="card-body">
                    <p><strong>Mã đơn:</strong> <?= $order['maorder'] ?></p>
                    <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
                    <p><strong>Tình trạng:</strong> 
                        <span class="badge 
                            <?= $order['status'] == '0' ? 'bg-secondary' : '' ?>
                            <?= $order['status'] == '1' ? 'bg-primary' : '' ?>
                            <?= $order['status'] == '2' ? 'bg-success' : '' ?>
                            <?= $order['status'] == '3' ? 'bg-danger' : '' ?>">
                            <?= $db::getStatusText($order['status']) ?>
                        </span>
                    </p>
                    <p><strong>Địa điểm giao:</strong> <?= $order['address'] ?></p>
                    
                    <?php if (!empty($order['mabill'])): ?>
                    <div class="mt-3 p-3 bg-light rounded">
                        <h5>Thông tin thanh toán</h5>
                        <p><strong>Mã hóa đơn:</strong> <?= htmlspecialchars($order['mabill']) ?></p>
                        <p><strong>Tổng tiền:</strong> 
                            <?= isset($order['tongtien']) ? number_format((float)$order['tongtien'], 0, ',', '.').'đ' : '0đ' ?>
                        </p>
                        <p><strong>Ngày thanh toán:</strong> 
                            <?= !empty($order['ngaymua']) ? date('d/m/Y H:i', strtotime($order['ngaymua'])) : 'Chưa xác định' ?>
                        </p>
                        <p><strong>Phương thức:</strong> 
                            <?= !empty($order['paybyname']) ? htmlspecialchars($order['paybyname']) : 'Chưa xác định' ?>
                        </p>
                    </div>
                    <?php else: ?>
                    <div class="mt-3 p-3 bg-warning bg-opacity-10 rounded">
                        <p class="text-warning mb-0">Đơn hàng chưa được thanh toán</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">Thông tin khách hàng</div>
                <div class="card-body">
                    <p><strong>Tên:</strong> <?= $order['customer_name'] ?></p>
                    <p><strong>Địa chỉ:</strong> <?= $order['address'] ?></p>
                    <p><strong>Điện thoại:</strong> <?= $order['phone'] ?></p>
                    <p><strong>Email:</strong> <?= $order['email'] ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header">Sản phẩm</div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td><?= $item['tensp'] ?></td>
                        <td><?= $item['soluong'] ?></td>
                        <td><?= number_format($item['dongiasanpham'], 0, ',', '.') ?>đ</td>
                        <td><?= number_format($item['dongiasanpham'] * $item['soluong'], 0, ',', '.') ?>đ</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <?php 
                    // Calculate total from items if not set
                    $calculated_total = 0;
                    foreach ($order['items'] as $item) {
                        $calculated_total += $item['dongiasanpham'] * $item['soluong'];
                    }
                    // Use bill total if available, otherwise use calculated total
                    $display_total = $order['tongtien'] ?? $calculated_total;
                    ?>
                    <tr>
                        <th colspan="3">Tổng cộng:</th>
                        <th><?= number_format($calculated_total, 0, ',', '.') ?>đ</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="order_list.php" class="btn btn-secondary">Quay lại</a>
    </div>
</div>