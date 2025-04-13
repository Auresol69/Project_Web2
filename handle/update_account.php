<?php
session_start();

header('Content-Type: application/json'); // Đảm bảo phản hồi là JSON

if (!isset($_SESSION['macustomer'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để cập nhật thông tin']);
    exit();
}

include("../database/database.php");

$user_id = $_SESSION['macustomer'];
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$province_id = isset($_POST['province_id']) ? trim($_POST['province_id']) : null;
$district_id = isset($_POST['district_id']) ? trim($_POST['district_id']) : null;
$address_detail = isset($_POST['address_detail']) ? trim($_POST['address_detail']) : null;

// Kiểm tra các trường bắt buộc
if (empty($name) || empty($email) || empty($phone)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Tên, email và số điện thoại không được để trống']);
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Email không hợp lệ']);
    exit();
}


if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Số điện thoại không hợp lệ']);
    exit();
}

if ($province_id) {
    $stmt = $conn->prepare("SELECT province_id FROM provinces WHERE province_id = ?");
    $stmt->bind_param("s", $province_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Tỉnh/thành phố không hợp lệ']);
        exit();
    }
    $stmt->close();
}

if ($district_id) {
    $stmt = $conn->prepare("SELECT district_id FROM districts WHERE district_id = ? AND province_id = ?");
    $stmt->bind_param("ss", $district_id, $province_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Quận/huyện không hợp lệ hoặc không thuộc tỉnh/thành phố đã chọn']);
        exit();
    }
    $stmt->close();
}

// Cập nhật thông tin vào bảng customer
$query = "UPDATE customer SET name = ?, email = ?, phone = ?, province_id = ?, district_id = ?, address_detail = ? WHERE macustomer = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'SQL error: ' . $conn->error]);
    exit();
}
$stmt->bind_param("sssssss", $name, $email, $phone, $province_id, $district_id, $address_detail, $user_id);
if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'SQL execute error: ' . $stmt->error]);
    exit();
}

echo json_encode(['status' => 'success', 'message' => 'Cập nhật thông tin tài khoản thành công']);
$stmt->close();
$conn->close();
exit();
?>