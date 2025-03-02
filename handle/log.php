<?php
    include("../database/database.php");
    header("Content-Type: application/json");

    $ItemPerPage = 6;
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $page = max(1, $page);

    $offset = ($page -1) * $ItemPerPage;

    $totalQuery  = $conn->query("SELECT COUNT(*) as total FROM product");
    $row = mysqli_fetch_assoc($totalQuery);
    $TotalPage = ceil($row['total']/ $ItemPerPage);
    
    $sql = "SELECT * FROM product LIMIT $offset, $ItemPerPage";
    $result = $conn->query($sql);

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
    $response["total"] = $TotalPage;
    $response["page"] = $page;

    echo json_encode($response);
    mysqli_close($conn);
?>