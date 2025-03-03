<?php
    include("../database/database.php");
    header("Content-Type: application/json");

    $ItemPerPage = 6;
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $page = max(1, $page);

    $offset = ($page -1) * $ItemPerPage;

    $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';

    $whereClause = "";
    if (!empty($keyword)){
        $whereClause = "WHERE ProductName LIKE '%$keyword%'";
    }

    $totalQuery  = $conn->query("SELECT COUNT(*) as total FROM product $whereClause");
    $row = mysqli_fetch_assoc($totalQuery);
    $TotalPage = ceil($row['total']/ $ItemPerPage);
    
    $sql = "SELECT * FROM product $whereClause LIMIT $offset, $ItemPerPage";
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