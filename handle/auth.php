<?php
include("../database/database.php");
session_start();

if ($_POST['action'] === 'check') {
    if (isset($_SESSION['macustomer'])) {
        $macustomer = $_SESSION['macustomer'];

        $check_sql = "SELECT * FROM customer where macustomer = ?";
        $stmt=$conn->prepare($check_sql);
        $stmt->bind_param("s", $macustomer);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows >0){
            $customer= $result->fetch_assoc();
            echo json_encode(["loggedIn" => true, "name" => $customer['name']]);

        }
        else {
            echo json_encode(["loggedIn" => false]);
        }
        exit();
    }else{
        echo json_encode(["loggedIn" => false]);
        exit();
    }
} else if ($_POST['action'] === 'logout') {
    session_unset();
    session_destroy();
    echo json_encode(["loggedIn" => false]);
    exit();
}
?>