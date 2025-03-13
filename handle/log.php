    <?php
        include("../database/database.php");
        header("Content-Type: application/json");

        $ItemPerPage = 6;
        $page = isset($_POST['page']) ? $_POST['page'] : 1;
        $page = max(1, $page);

        $offset = ($page -1) * $ItemPerPage;


        $conditions = [];

        // Tìm kiếm sản phẩm theo tên
        $keyword = isset($_POST['keyword']) ? $_POST['keyword'] : '';
        if (!empty($keyword)){
            $conditions[] = "ProductName LIKE '%$keyword%'";
        }

        // Tìm kiếm sản phẩm theo loại
        $type = isset($_POST['type']) ? $_POST['type'] : '';
        if (!empty($type) && $type != 'all'){
            $conditions[] = "ProductType = '$type'";
        }

        // Tìm kiếm sản phẩm theo khoảng giá
        $minPrice = isset($_POST['min']) ? $_POST['min'] : '';
        $maxPrice = isset($_POST['max'])? $_POST['max'] : '';
        if (!empty($minPrice) && empty($maxPrice)){
            $conditions[] = "Price >= '$minPrice'";
        }
        if (!empty($maxPrice) && empty($minPrice)){
            $conditions[] = "Price <= '$maxPrice'";
        }
        if (!empty($minPrice) &&!empty($maxPrice)){
            $conditions[] = "Price BETWEEN '$minPrice' AND '$maxPrice'";
        }


        $whereClause = !empty($conditions) ? "WHERE ". implode(" AND ", $conditions) : "";


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
        
        $types =$conn->query("SELECT DISTINCT ProductType as types From product ORDER BY ProductType ASC");
        
        $response["header__menu__sub"] = [];
        while($row = mysqli_fetch_assoc($types)){
            $response["header__menu__sub"][]=[
                "type" => $row['types']];
        }
        
        echo json_encode($response);
        mysqli_close($conn);
    ?>