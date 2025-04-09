<?php
    include('../database/database.php');  // Đảm bảo đã kết nối cơ sở dữ liệu

    // Lấy mã loại sản phẩm từ URL
    if (isset($_GET['maloaisp'])) {
        $maloaisp = $_GET['maloaisp'];
    } else {
        $maloaisp = ''; // Nếu không có tham số, lấy tất cả sản phẩm
    }

    // Truy vấn sản phẩm theo mã loại sản phẩm
    if ($maloaisp != '') {
        $sql = "SELECT * FROM product WHERE maloaisp = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $maloaisp); // "s" là kiểu dữ liệu cho string
    } else {
        $sql = "SELECT * FROM product";  // Lấy tất cả sản phẩm nếu không có mã loại sản phẩm
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    $result = $stmt->get_result();
?>

<?php include('../layout/headerr.php');?>

<link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="../css/trangchu.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<script src="../js/scripts.js?v=<?php echo time(); ?>"></script>


<div class="content">
    <div class="content__input">
        <h1>CÂY TRỒNG<br> DÀNH CHO BẠN</h1>
        <div class="content__input-img">
            <img src="../img/cay.png" alt="">
        </div>
        <p class="content__slogan">Khám phá thế giới cây xanh, làm đẹp không gian sống!</p>

        <div class="content__input__main">
            <input id="keyword" type="text" placeholder="Tìm kiếm">
            <i class="fa-solid fa-magnifying-glass" onclick="LoadProducts(1)"></i>
        </div>
        <!-- <div class="content__input__sort">
            <input id="content__input__main__sort_min" type="text" placeholder="min">
            <input id="content__input__main__sort_max" type="text" placeholder="max">
            <select id="content__input__main__sort_type">
            </select>
        </div> -->
    </div>

    <h2 class="product-title">SẢN PHẨM</h2>

    <div id="content__product">
        <?php
        // Kiểm tra nếu có sản phẩm
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='sanpham-card'> ";
            
                echo "<div class='sanpham-img-container'>";
                echo "<img src='/treeshopuser/Project_Web2/" . $row['img'] . "' alt='" . $row['tensp'] . "' class='sanpham-img'>";
                echo "<div class='overlay-xemnhanh' onclick='openModal(" . json_encode($row) . ")'>Xem chi tiết</div>";
                echo "</div>";
            
                echo "<h3 class='sanpham-ten'>" . $row['tensp'] . "</h3>";
                echo "<p class='sanpham-gia'>" . number_format($row['dongiasanpham'], 0, ',', '.') . "₫</p>";
                echo "<button class='btn-them'>Thêm vào giỏ</button>";
                
                echo "</div>";
            }            
        } else {
            echo "<p>Không có sản phẩm nào trong danh mục này.</p>";
        }

        // Đóng statement và kết nối
        $stmt->close();
        $conn->close();
        ?>
    </div>
        
    </div>
        <div class="prev" onclick="Lui()">
            < </div>
                <div id="page"> </div>
                <div class="next" onclick="Tien()"> > </div>
        </div>
    </div>
</div>

<?php include('../layout/footerr.php');?>


<!-- Modal chi tiết sản phẩm -->
<div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>

        <div class="modal-layout">
            <div class="modal-left">
                <img id="modal-img" src="" alt="Ảnh sản phẩm">
            </div>
            
            <div class="modal-right">
                <h1 id="modal-title" class="sanpham-ten">Tên sản phẩm</h1>
                <p id="modal-code" class="product-code">Mã sản phẩm</p>
                <p id="modal-quantity" class="product-quantity">Số lượng: 0</p>
                <p id="modal-price" class="sanpham-gia">0.000₫</p>
                <p id="modal-description">Mô tả sản phẩm...</p>

                <div class="button-group">
                    <div class="cart-icon-sp">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <button class="btn-muanhanh">Mua ngay</button>
                </div>

            </div>
        </div>
    </div>
</div>

