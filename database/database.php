<?php 
    $db_name = "treeshop1";
    $db_user = "root";
    $db_pass = "";
    $db_host = "localhost";

    // Kết nối đến cơ sở dữ liệu MySQL
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    // Kiểm tra kết nối
    if (!$conn) {
        die("Lỗi kết nối cơ sở dữ liệu: " . mysqli_connect_error());
    }
?>
