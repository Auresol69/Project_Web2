<?php
include("../database/database.php");
header("Content-Type: application/json");

// Xác định hành động (thêm, xóa, lấy dữ liệu, tăng, giảm)
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action == 'add') {
    $productID = isset($_POST['productID']) ? intval($_POST['productID']) : 0;

    if ($productID > 0) {
        $checkQuery = $conn->query("SELECT * FROM Cart WHERE ProductID = $productID");
        if ($checkQuery->num_rows > 0) {
            $conn->query("UPDATE Cart SET Quantity = Quantity + 1 WHERE ProductID = $productID");
        } else {
            $conn->query("INSERT INTO Cart (ProductID, Quantity) VALUES ($productID, 1)");
        }
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "ID sản phẩm không hợp lệ"]);
    }
} elseif ($action == 'remove') {
    $productID = isset($_POST['productID']) ? intval($_POST['productID']) : 0;

    if ($productID > 0) {
        $conn->query("DELETE FROM Cart WHERE ProductID = $productID");
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "ID sản phẩm không hợp lệ"]);
    }
} elseif ($action == 'increase') {
    $productID = isset($_POST['productID']) ? intval($_POST['productID']) : 0;

    if ($productID > 0) {
        $conn->query("UPDATE Cart SET Quantity = Quantity + 1 WHERE ProductID = $productID");
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "ID sản phẩm không hợp lệ"]);
    }
} elseif ($action == 'decrease') {
    $productID = isset($_POST['productID']) ? intval($_POST['productID']) : 0;

    if ($productID > 0) {
        $checkQuantity = $conn->query("SELECT Quantity FROM Cart WHERE ProductID = $productID")->fetch_assoc();
        if ($checkQuantity["Quantity"] > 1) {
            $conn->query("UPDATE Cart SET Quantity = Quantity - 1 WHERE ProductID = $productID");
        } else {
            $conn->query("DELETE FROM Cart WHERE ProductID = $productID");
        }
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "ID sản phẩm không hợp lệ"]);
    }
} elseif ($action == 'get') {
    $cartQuery = $conn->query("
        SELECT Cart.ProductID, Product.ProductName, Product.Price, Product.ProductImage, Cart.Quantity 
        FROM Cart 
        JOIN Product ON Cart.ProductID = Product.ProductID
    ");
    
    $cartItems = [];
    while ($row = mysqli_fetch_assoc($cartQuery)) {
        $cartItems[] = $row;
    }
    
    echo json_encode(["cart" => $cartItems]);
}

mysqli_close($conn);
?>
