<?php
session_start();
header('Content-Type: application/json; charset=UTF-8');
include("../database/database.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$response = ['status' => 'error', 'message' => 'Unknown error'];

if (!isset($conn) || $conn->connect_error) {
    $response['message'] = 'Không thể kết nối đến cơ sở dữ liệu!';
    echo json_encode($response);
    exit;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';
$productId = isset($_POST['productID']) ? $_POST['productID'] : '';
$userId = isset($_SESSION['macustomer']) ? $_SESSION['macustomer'] : null;
error_log("UserID in cart.php (action: $action): " . $userId); // Ghi log

if (!$userId) {
    $response['message'] = 'Bạn cần đăng nhập để thực hiện thao tác này!';
    echo json_encode($response);
    exit;
}

if ($action === 'add') {
    $cartQuery = "SELECT magiohang FROM cart WHERE mauser = ?";
    $stmt = $conn->prepare($cartQuery);
    $stmt->bind_param("s", $userId);
    if (!$stmt->execute()) {
        $response['message'] = 'Lỗi truy vấn SQL: ' . $stmt->error;
        echo json_encode($response);
        exit;
    }
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $cartId = 'CART' . uniqid();
        $insertCart = "INSERT INTO cart (magiohang, mauser) VALUES (?, ?)";
        $stmt = $conn->prepare($insertCart);
        $stmt->bind_param("ss", $cartId, $userId);
        if (!$stmt->execute()) {
            $response['message'] = 'Lỗi truy vấn SQL: ' . $stmt->error;
            echo json_encode($response);
            exit;
        }
    } else {
        $cart = $result->fetch_assoc();
        $cartId = $cart['magiohang'];
    }

    $checkProduct = "SELECT soluong FROM product_cart WHERE magiohang = ? AND masp = ?";
    $stmt = $conn->prepare($checkProduct);
    $stmt->bind_param("ss", $cartId, $productId);
    if (!$stmt->execute()) {
        $response['message'] = 'Lỗi truy vấn SQL: ' . $stmt->error;
        echo json_encode($response);
        exit;
    }
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $newQuantity = $row['soluong'] + 1;
        $updateProduct = "UPDATE product_cart SET soluong = ? WHERE magiohang = ? AND masp = ?";
        $stmt = $conn->prepare($updateProduct);
        $stmt->bind_param("iss", $newQuantity, $cartId, $productId);
        if (!$stmt->execute()) {
            $response['message'] = 'Lỗi truy vấn SQL: ' . $stmt->error;
            echo json_encode($response);
            exit;
        }
    } else {
        // Kiểm tra trước khi thêm vào product_cart
        $checkProduct = $conn->prepare("SELECT 1 FROM product WHERE masp = ?");
        $checkProduct->bind_param("s", $productId);
        $checkProduct->execute();
        $result = $checkProduct->get_result();
    
        if ($result->num_rows == 0) {
            $response['message'] = 'Sản phẩm không tồn tại trong bảng product.';
            echo json_encode($response);
            exit;
        }
    
        // Nếu tồn tại, thêm vào giỏ
        $insertProduct = "INSERT INTO product_cart (masp, magiohang, soluong) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($insertProduct);
        $stmt->bind_param("ss", $productId, $cartId);
    
        error_log("Giá trị masp (productId): " . $productId);
    
        if (!$stmt->execute()) {
            $response['message'] = 'Lỗi truy vấn SQL: ' . $stmt->error;
            echo json_encode($response);
            exit;
        }
    }
    

    $response['status'] = 'success';
    $response['message'] = 'Thêm sản phẩm thành công!';
}

if ($action === 'get') {
    $cartQuery = "SELECT p.masp, p.tensp, p.dongiasanpham, p.img, pc.soluong 
                FROM product_cart pc 
                JOIN product p ON pc.masp = p.masp 
                JOIN cart c ON pc.magiohang = c.magiohang 
                WHERE c.mauser = ?";
    $stmt = $conn->prepare($cartQuery);
    $stmt->bind_param("s", $userId);
    if (!$stmt->execute()) {
        $response['message'] = 'Lỗi truy vấn SQL: ' . $stmt->error;
        echo json_encode($response);
        exit;
    }
    $result = $stmt->get_result();

    $cartItems = [];
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = [
            'ProductID' => $row['masp'],
            'ProductName' => $row['tensp'],
            'Price' => $row['dongiasanpham'],
            'ProductImage' => $row['img'],
            'Quantity' => $row['soluong']
        ];
    }
    error_log("Cart items for user $userId: " . json_encode($cartItems)); // Ghi log
    $response['status'] = 'success';
    $response['cart'] = $cartItems; 
}

if ($action === 'increase') {
    $cartQuery = "SELECT magiohang FROM cart WHERE mauser = ?";
    $stmt = $conn->prepare($cartQuery);
    $stmt->bind_param("s", $userId);
    if (!$stmt->execute()) {
        $response['message'] = 'Lỗi truy vấn SQL: ' . $stmt->error;
        echo json_encode($response);
        exit;
    }
    $cart = $stmt->get_result()->fetch_assoc();
    $cartId = $cart['magiohang'];

    $updateQuery = "UPDATE product_cart SET soluong = soluong + 1 WHERE magiohang = ? AND masp = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ss", $cartId, $productId);
    if (!$stmt->execute()) {
        $response['message'] = 'Lỗi truy vấn SQL: ' . $stmt->error;
        echo json_encode($response);
        exit;
    }

    $response['status'] = 'success';
    $response['message'] = 'Tăng số lượng thành công!';
}



if ($action === 'decrease') {
    $cartQuery = "SELECT magiohang FROM cart WHERE mauser = ?";
    $stmt = $conn->prepare($cartQuery);
    $stmt->bind_param("s", $userId);
    if (!$stmt->execute()) {
        $response['message'] = 'Lỗi truy vấn SQL: ' . $stmt->error;
        echo json_encode($response);
        exit;
    }
    $cart = $stmt->get_result()->fetch_assoc();
    $cartId = $cart['magiohang'];

    $checkQuery = "SELECT soluong FROM product_cart WHERE magiohang = ? AND masp = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ss", $cartId, $productId);
    if (!$stmt->execute()) {
        $response['message'] = 'Lỗi truy vấn SQL: ' . $stmt->error;
        echo json_encode($response);
        exit;
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['soluong'] > 1) {
        $updateQuery = "UPDATE product_cart SET soluong = soluong - 1 WHERE magiohang = ? AND masp = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("ss", $cartId, $productId);
        if (!$stmt->execute()) {
            $response['message'] = 'Lỗi truy vấn SQL: ' . $stmt->error;
            echo json_encode($response);
            exit;
        }
        $response['status'] = 'success';
        $response['message'] = 'Giảm số lượng thành công!';
    } else {
        $response['message'] = 'Số lượng tối thiểu là 1!';
    }
}

if ($action === 'remove') {
    $cartQuery = "SELECT magiohang FROM cart WHERE mauser = ?";
    $stmt = $conn->prepare($cartQuery);
    $stmt->bind_param("s", $userId);
    if (!$stmt->execute()) {
        $response['message'] = 'Lỗi truy vấn SQL: ' . $stmt->error;
        echo json_encode($response);
        exit;
    }
    $cart = $stmt->get_result()->fetch_assoc();
    $cartId = $cart['magiohang'];

    $deleteQuery = "DELETE FROM product_cart WHERE magiohang = ? AND masp = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("ss", $cartId, $productId);
    if (!$stmt->execute()) {
        $response['message'] = 'Lỗi truy vấn SQL: ' . $stmt->error;
        echo json_encode($response);
        exit;
    }

    $response['status'] = 'success';
    $response['message'] = 'Xóa sản phẩm thành công!';
}

echo json_encode($response);
?>