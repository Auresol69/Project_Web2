<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="../css/admin_style.css" >
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
                            <li><a href="dashboard.php">Thông tin hệ thống</a></li>
                            <li><a href="menu_listing.php">Danh mục</a></li>
                            <li><a href="product_listing.php">Sản phẩm</a></li>
                            <li><a href="order_listing.php">Đơn hàng</a></li>
                            <li><a href="member_listing.php">Quản lý thành viên</a></li>
                        </ul>
                    </div>
                </div>
                <div class="main-contaniner">

                </div>
            </div>
        </div>
        <?php include './footer.php'?>
    </body>
</html>