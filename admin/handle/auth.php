<?php
include('../database/database.php');

session_start();

header('Content-Type: application/json');

if ($_POST['action'] === 'check') {
    if (isset($_SESSION['mastaff'])) {
        $mastaff = $_SESSION['mastaff'];
        $check_sql = "SELECT * FROM staff where mastaff = ?";
        $stmt = $conn->prepare($check_sql);
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'SQL error: ' . $conn->error]);
            exit();
        }
        $stmt->bind_param("s", $mastaff);
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'SQL execute error: ' . $stmt->error]);
            exit();
        }
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $staff = $result->fetch_assoc();
            $_SESSION['staffname'] = $staff['staffname'];
            echo json_encode(["loggedIn" => true, "staffname" => $staff['staffname']]);
        } else {
            echo json_encode(["loggedIn" => false]);
        }
        $stmt->close();
    } else {
        echo json_encode(["loggedIn" => false]);
    }
    exit();
} else if ($_POST['action'] === 'logout') {
    session_unset();
    session_destroy();
    echo json_encode(["loggedIn" => false]);
    exit();
}else if($_POST['action'] === 'check_powergroup'){
    if (isset($_SESSION['mastaff'])){
        $mastaff = $_SESSION['mastaff'];

        $check_sql = "SELECT pfp.funcid, pfp.permissionid FROM staff s LEFT JOIN powergroup_func_permission pfp ON s.powergroupid = pfp.powergroupid WHERE s.mastaff = ?";
        $stmt = $conn->prepare(query: $check_sql);
        if (!$stmt) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'SQL error: ' . $conn->error]);
            exit();
        }
        $stmt->bind_param("s",$mastaff);
        if (!$stmt->execute()) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'SQL execute error: ' . $stmt->error]);
            exit();
        }
        $result = $stmt->get_result();
        if ($result->num_rows>0){
            while($powergroupid=$result->fetch_assoc()){
               $permissionid = $powergroupid['permissionid'];
               $funcid = $powergroupid['funcid'];
               if (!isset($response[$permissionid]))
                    $response[$permissionid] = array();
                $response[$permissionid][] = $funcid;
            }
        }
        echo json_encode($response);
    }else{
        http_response_code(401);  // Mã lỗi 401 (Unauthorized)
        echo json_encode(['status' => 'error', 'message' => 'Chưa đăng nhập. Vui lòng đăng nhập trước.']);
    }
}

$conn->close();
exit();
?>