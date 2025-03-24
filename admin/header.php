<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../css/admin_style.css">
    </head>
    <body>
        <div id="admin-heading-panel">
            <div class="container">
                <div class="left-panel">
                    Xin chào <span>Admin</span>
                </div>
                <div class="right-panel">
                    <img height="24" src="" />
                    <a href="index.php">Trang chủ</a>
                    <img height="24" src="" />
                    <a href="logout.php">Đăng xuất</a>
                </div>
            </div>
        </div>
        <div id="content-wrapper">
            <div class="container">
                <div class="left-menu">
                    <div class="menu-heading">Admin Menu</div>
                    <div class="menu-items">
                        <ul>
                            <li><a href="?page=home">Thông tin hệ thống</a></li>
                            <li><a href="?page=danhmuc">Danh mục</a></li>
                            <li><a href="?page=sanpham">Sản phẩm</a></li>
                            <li><a href="?page=donhang">Đơn hàng</a></li>
                            <li><a href="?page=customer">Quản lý Khách hàng</a></li>
                        </ul>
                    </div>
                </div>
                <div class="main-contaniner">
                <?php
                    $page= isset( $_GET['page'] ) ? $_GET['page'] :'home';
                    switch ( $page ) {
                        case'danhmuc':
                            include 'danhmuc.php';
                            break;
                        case'sanpham':
                            include 'product/product_list.php';
                            break;
                        case'donhang':
                            include 'donhang.php';
                            break;
                        case'customer':
                            include 'customer/customer_listing.php';
                            break;
                        default:
                            echo 'Hello';
                            break;
                    }
                ?>
                </div>
            </div>
        </div>
        <?php include './footer.php'?>
    </body>
</html>