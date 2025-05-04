<?php 
    $db_name = "treeshop2";
    $db_user = "root";
    $db_pass = "";
    $db_host = "localhost";
    $db_port = 3306; // Define default MySQL port

    // Kết nối đến cơ sở dữ liệu MySQL
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port);

    // Kiểm tra kết nối
    if (!$conn) {
        die("Lỗi kết nối cơ sở dữ liệu: " . mysqli_connect_error());
    }
?>
