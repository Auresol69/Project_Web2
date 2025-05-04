<?php
require_once __DIR__ . '/../../admin/connect_db.php';

header('Content-Type: application/json');

$db = new connect_db();

$order_id = $_POST['order_id'] ?? '';
$new_status = $_POST['status'] ?? '';

if (empty($order_id) || empty($new_status)) {
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin']);
    exit;
}

// Get current order status
$order = $db->getOrderDetails($order_id);
if (!$order) {
    echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại']);
    exit;
}

// Validate status progression (only allow forward)
$current_status = $order['status'];
if ($new_status < $current_status && $new_status != '3') {
    echo json_encode(['success' => false, 'message' => 'Không thể cập nhật ngược trạng thái']);
    exit;
}

// Update status
if ($db->updateOrderStatus($order_id, $new_status)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Lỗi khi cập nhật']);
}
