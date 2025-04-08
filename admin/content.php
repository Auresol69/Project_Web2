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
                    <li><a href="?page=customer">Quản lý tài khoản</a></li>
                    <li><a href="?page=phanquyen">Phân quyền</a></li>
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
                case 'phanquyen':
                    include 'phanquyen/phanquyen_customer.php';
                    break;
                case 'home':
                default:
                    echo 'Welcome to Admin Page';
                    break;
            }
        ?>
        </div>
    </div>
</div>