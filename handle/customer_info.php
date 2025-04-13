<?php
session_start();

header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

if (!isset($_SESSION['macustomer'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để xem thông tin']);
    exit();
}

include("../database/database.php");

$user_id = $_SESSION['macustomer'];

// Truy vấn thông tin tài khoản
$query = "SELECT c.*, p.name AS province_name, d.name AS district_name 
          FROM customer c 
          LEFT JOIN provinces p ON c.province_id = p.province_id 
          LEFT JOIN districts d ON c.district_id = d.district_id 
          WHERE c.macustomer = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'SQL error: ' . $conn->error]);
    exit();
}
$stmt->bind_param("s", $user_id);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'SQL execute error: ' . $stmt->error]);
    exit();
}
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    http_response_code(404);
    echo json_encode(['status' => 'error', 'message' => 'Không tìm thấy thông tin tài khoản']);
    $stmt->close();
    $conn->close();
    exit();
}

// Truy vấn danh sách tỉnh/thành phố
$provinces_query = "SELECT province_id, name FROM provinces";
$provinces_result = $conn->query($provinces_query);
$provinces = [];
while ($row = $provinces_result->fetch_assoc()) {
    $provinces[] = $row;
}

// Truy vấn danh sách quận/huyện
$districts_query = "SELECT district_id, name, province_id FROM districts";
$districts_result = $conn->query($districts_query);
$districts = [];
while ($row = $districts_result->fetch_assoc()) {
    $districts[] = $row;
}

echo json_encode([
    'status' => 'success',
    'data' => [
        'username' => $user['username'] ?? 'Chưa cập nhật',
        'name' => $user['name'] ?? 'Chưa cập nhật',
        'email' => $user['email'] ?? 'Chưa cập nhật',
        'phone' => $user['phone'] ?? 'Chưa cập nhật',
        'province_id' => $user['province_id'] ?? '',
        'province_name' => $user['province_name'] ?? 'Chưa chọn',
        'district_id' => $user['district_id'] ?? '',
        'district_name' => $user['district_name'] ?? 'Chưa chọn',
        'address_detail' => $user['address_detail'] ?? ''
    ],
    'provinces' => $provinces,
    'districts' => $districts
]);

$stmt->close();
$conn->close();
exit();
?>