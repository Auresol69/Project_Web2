<?php 
include('../database/database.php');

if ($conn->connect_error) {
    die(json_encode([
        "status" => "error",
        "message" => "Kết nối database thất bại: " . $conn->connect_error
    ]));
}

function kiem_tra($input, $regex) {
    return preg_match($regex, $input);
}

$response = ["status" => "error", "errors" => []];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['staffname']) && isset($_POST['password'])) {

            $staffname = $_POST['staffname'];
            $password = $_POST['password'];

            $check_sql = "SELECT * FROM staff WHERE staffname = ?";
            $stmt = $conn->prepare($check_sql);
            $stmt->bind_param("s", $staffname);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $staff = $result->fetch_assoc();
                if (password_verify($password,$staff['password'])) {
                    $response = ["status" => "success", "message" => "Đăng nhập thành công!"];
                    session_start();
                    $_SESSION['mastaff'] = $staff['mastaff'];
                    $response['staffname'] = $staff['staffname'];
                } else {
                    $response["errors"]["password"] = "Mật khẩu không chính xác!";
                }
            } else {
                $response["errors"]["staffname"] = "Tài khoản không tồn tại!";
            }
            $stmt->close();
        } }else {
            $response = ["status" => "error", "message" => "Invalid mode!"];
        }

// Trả kết quả JSON
echo json_encode($response);
$conn->close();