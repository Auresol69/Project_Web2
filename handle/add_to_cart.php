<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../database/database.php"); // Kết nối database

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);

    // Kiểm tra sản phẩm có tồn tại không
    $sql = "SELECT * FROM Product WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    if (!$product) {
        echo json_encode(["status" => "error", "message" => "Sản phẩm không tồn tại"]);
        exit();
    }

    $price = $product['Price'];
    $image = $product['ProductImage'];

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $check_sql = "SELECT * FROM Cart WHERE ProductID = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $product_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Nếu sản phẩm đã có, cập nhật số lượng
        $update_sql = "UPDATE Cart SET Quantity = Quantity + 1 WHERE ProductID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $product_id);
        $update_stmt->execute();
    } else {
        // Nếu sản phẩm chưa có, thêm mới vào giỏ hàng
        $insert_sql = "INSERT INTO Cart (ProductID, Quantity, Price, ProductImage) VALUES (?, 1, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ids", $product_id, $price, $image);
        $insert_stmt->execute();
    }

    // Lấy tổng số lượng sản phẩm trong giỏ hàng
    $count_sql = "SELECT SUM(Quantity) AS total FROM Cart";
    $count_result = $conn->query($count_sql);
    $total_items = $count_result->fetch_assoc()['total'] ?? 0;

    echo json_encode([
        "status" => "success",
        "message" => "Sản phẩm đã được thêm vào giỏ hàng",
        "total_items" => $total_items
    ]);
}
?>
