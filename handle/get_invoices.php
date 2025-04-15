<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
include("../database/database.php");

$response = ['status' => 'error', 'message' => 'Unknown error'];

try {
    if (!isset($_SESSION['macustomer'])) {
        throw new Exception('Bạn cần đăng nhập!');
    }

    $userId = $_SESSION['macustomer'];

    // Lấy danh sách hóa đơn của khách hàng
    $sql = "SELECT b.mabill, b.maorder, b.tongtien, b.bill_date, b.receiver_name, b.phone_number, p.paybyname, p.address
            FROM bill b
            LEFT JOIN payby p ON b.mapayby = p.mapayby
            WHERE b.macustomer = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        throw new Exception('Lỗi chuẩn bị truy vấn: ' . $conn->error);
    }
    $stmt->bind_param("s", $userId);
    if (!$stmt->execute()) {
        throw new Exception('Lỗi thực thi truy vấn: ' . $stmt->error);
    }
    $result = $stmt->get_result();
    $invoices = [];

    while ($bill = $result->fetch_assoc()) {
        // Lấy chi tiết sản phẩm trong hóa đơn
        $sqlItems = "SELECT masp, tensp, soluong, dongia, thanhtien
                     FROM bill_details
                     WHERE mabill = ?";
        $stmtItems = $conn->prepare($sqlItems);
        if (!$stmtItems) {
            throw new Exception('Lỗi chuẩn bị truy vấn chi tiết: ' . $conn->error);
        }
        $stmtItems->bind_param("s", $bill['mabill']);
        if (!$stmtItems->execute()) {
            throw new Exception('Lỗi thực thi truy vấn chi tiết: ' . $stmtItems->error);
        }
        $resultItems = $stmtItems->get_result();
        $items = [];

        while ($item = $resultItems->fetch_assoc()) {
            $items[] = [
                'masp' => $item['masp'],
                'tensp' => $item['tensp'],
                'soluong' => $item['soluong'],
                'dongiasanpham' => $item['dongia'],
                'thanhtien' => $item['thanhtien']
            ];
        }
        $stmtItems->close();

        $invoices[] = [
            'bill_id' => $bill['mabill'],
            'order_id' => $bill['maorder'],
            'order_date' => $bill['bill_date'],
            'receiver_name' => $bill['receiver_name'] ?? 'Khách hàng',
            'phone_number' => $bill['phone_number'] ?? 'Không có số điện thoại',
            'address' => $bill['address'] ?? 'Không có thông tin địa chỉ',
            'total_price' => $bill['tongtien'],
            'items' => $items,
            'payment_method' => $bill['paybyname'] ?? 'Không xác định'
        ];
    }
    $stmt->close();

    $response['status'] = 'success';
    $response['invoices'] = $invoices;
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
$conn->close();
?>