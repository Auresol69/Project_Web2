<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
include("../database/database.php");

$response = ['status' => 'error', 'message' => 'Unknown error'];

if (!isset($_SESSION['macustomer'])) {
    $response['message'] = 'Bạn cần đăng nhập để đặt hàng!';
    echo json_encode($response);
    exit;
}

$userId = $_SESSION['macustomer'];

// Lấy thông tin từ yêu cầu POST
$action = isset($_POST['action']) ? $_POST['action'] : '';
$addressOption = isset($_POST['address_option']) ? $_POST['address_option'] : '';
$receiverName = isset($_POST['receiver_name']) ? trim($_POST['receiver_name']) : '';
$phoneNumber = isset($_POST['phone_number']) ? trim($_POST['phone_number']) : '';
$addressDetail = isset($_POST['address_detail']) ? trim($_POST['address_detail']) : '';
$provinceId = isset($_POST['province_id']) ? trim($_POST['province_id']) : null;
$districtId = isset($_POST['district_id']) ? trim($_POST['district_id']) : null;

// Kiểm tra các trường bắt buộc
if (!$receiverName || !$phoneNumber || !$addressDetail || !$provinceId || !$districtId) {
    $response['message'] = 'Vui lòng điền đầy đủ thông tin bắt buộc!';
    echo json_encode($response);
    exit;
}

// Kiểm tra định dạng số điện thoại
if (!preg_match('/^[0-9]{10,11}$/', $phoneNumber)) {
    $response['message'] = 'Số điện thoại không hợp lệ!';
    echo json_encode($response);
    exit;
}

// Lấy giỏ hàng
$sqlCart = "SELECT * FROM cart WHERE mauser = ? AND maorder IS NULL";
$stmtCart = $conn->prepare($sqlCart);
$stmtCart->bind_param("s", $userId);
$stmtCart->execute();
$resultCart = $stmtCart->get_result();

if ($resultCart->num_rows === 0) {
    $response['message'] = 'Giỏ hàng của bạn đang trống!';
    echo json_encode($response);
    exit;
}

$cart = $resultCart->fetch_assoc();
$cartId = $cart['magiohang'];

// Tính tổng tiền
$totalPrice = 0;
$sqlItems = "SELECT pc.soluong, p.dongiasanpham 
             FROM product_cart pc 
             JOIN product p ON pc.masp = p.masp 
             WHERE pc.magiohang = ?";
$stmtItems = $conn->prepare($sqlItems);
$stmtItems->bind_param("s", $cartId);
$stmtItems->execute();
$resultItems = $stmtItems->get_result();

while ($item = $resultItems->fetch_assoc()) {
    $totalPrice += $item['soluong'] * $item['dongiasanpham'];
}

// Tạo mã order
$orderId = 'ORD' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

// Tạo mã bill
$billId = 'BIL' . str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);

// Tạo mã payby (giả sử mặc định là "Thanh toán khi nhận hàng")
$payById = 'PAY001';
$sqlPayBy = "INSERT INTO payby (mapayby, paybyname, address, details) 
             VALUES (?, 'Thanh toán khi nhận hàng', ?, '{}') 
             ON DUPLICATE KEY UPDATE address = ?";
$stmtPayBy = $conn->prepare($sqlPayBy);
$fullAddress = "$addressDetail, $districtId, $provinceId";
$stmtPayBy->bind_param("sss", $payById, $fullAddress, $fullAddress);
$stmtPayBy->execute();

// Tạo order
$sqlOrder = "INSERT INTO `order` (maorder, magiohang, mauser, status) VALUES (?, ?, ?, 1)";
$stmtOrder = $conn->prepare($sqlOrder);
$stmtOrder->bind_param("sss", $orderId, $cartId, $userId);
$stmtOrder->execute();

// Tạo bill
$sqlBill = "INSERT INTO bill (mabill, macustomer, maorder, mapayby, tongtien) 
            VALUES (?, ?, ?, ?, ?)";
$stmtBill = $conn->prepare($sqlBill);
$stmtBill->bind_param("ssssi", $billId, $userId, $orderId, $payById, $totalPrice);
$stmtBill->execute();

// Cập nhật cart với maorder
$sqlUpdateCart = "UPDATE cart SET maorder = ? WHERE magiohang = ?";
$stmtUpdateCart = $conn->prepare($sqlUpdateCart);
$stmtUpdateCart->bind_param("ss", $orderId, $cartId);
$stmtUpdateCart->execute();

// Cập nhật thông tin địa chỉ nếu chọn "Nhập địa chỉ mới"
if ($addressOption === 'new') {
    $sqlUpdateCustomer = "UPDATE customer 
                          SET name = ?, phone = ?, province_id = ?, district_id = ?, address_detail = ? 
                          WHERE macustomer = ?";
    $stmtUpdateCustomer = $conn->prepare($sqlUpdateCustomer);
    $stmtUpdateCustomer->bind_param("ssssss", $receiverName, $phoneNumber, $provinceId, $districtId, $addressDetail, $userId);
    $stmtUpdateCustomer->execute();
}

$response['status'] = 'success';
$response['message'] = 'Đặt hàng thành công!';
echo json_encode($response);

$stmtCart->close();
$stmtItems->close();
$stmtPayBy->close();
$stmtOrder->close();
$stmtBill->close();
$stmtUpdateCart->close();
if (isset($stmtUpdateCustomer)) {
    $stmtUpdateCustomer->close();
}
$conn->close();
?>