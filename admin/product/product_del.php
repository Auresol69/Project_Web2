<?php
require '../connect_db.php'; 

header('Content-Type: application/json'); // Thiết lập kiểu nội dung trả về là JSON

$response = ['success' => false, 'message' => '']; // Khởi tạo phản hồi mặc định

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $db = new connect_db(); // Tạo đối tượng connect_db

    try {
        // Kiểm tra số lượng sản phẩm trước khi xóa
        $product = $db->getById('product', $_GET['id']);
        if ($product === false) {
            $response['message'] = 'Sản phẩm không tồn tại.';
        } elseif ($product['soluong'] == 0) {
            // Nếu số lượng bằng 0, không xóa mà trả về thông báo đã bán hết
            // Removed restriction to allow deletion of sold-out products
            // Proceed to delete product
            $result = $db->delete('product', $_GET['id']);
            
            // Kiểm tra xem có bản ghi nào bị ảnh hưởng không
            if ($result->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Đã xóa sản phẩm thành công.';
            } else {
                $response['message'] = 'Sản phẩm không tìm thấy hoặc đã được xóa.';
            }
        }
    } catch (PDOException $e) {
        $response['message'] = "Không thể xóa sản phẩm. Lỗi: " . $e->getMessage();
    }
} else {
    $response['message'] = 'ID không hợp lệ.';
}

// Trả về phản hồi JSON
echo json_encode($response);
?>
