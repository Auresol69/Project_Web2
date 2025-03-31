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