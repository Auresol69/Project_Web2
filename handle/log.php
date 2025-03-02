<?php
    include("../database/database.php");
    header("Content-Type: application/json");

    $sql = "SELECT * FROM product";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo json_encode(["error" => "Query failed"]);
        exit;
    }

    $response = ["products" => []];

    while ($row = mysqli_fetch_assoc($result)) {
        $response["products"][] = [
            "id" => $row["ProductID"],
            "name" => $row["ProductName"],
            "price" => $row["Price"],
            "image" => $row["ProductImage"]
        ];
    }

    echo json_encode($response);
    mysqli_close($conn);
?>