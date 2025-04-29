<div id="content-wrapper">
            <div class="container">
                <div class="left-menu">
                    <div class="menu-heading">Admin Menu</div>
                    <div class="menu-items">
                        <ul>
                            <li><a href="?page=home" class="ajax-link">Thông tin hệ thống</a></li>
                            <li><a href="?page=danhmuc" class="ajax-link">Danh mục</a></li>
                            <li><a href="?page=sanpham" class="ajax-link">Sản phẩm</a></li>
                            <li><a href="?page=donhang" class="ajax-link">Đơn hàng</a></li>
                            <li><a href="?page=customer" class="ajax-link">Quản lý tài khoản</a></li>
                            <li><a href="?page=phanquyen" class="ajax-link">Phân quyền</a></li>
                        </ul>
                    </div>
                </div>
                <div class="main-contaniner" id="main-content">
                <?php
                    $page= isset( $_GET['page'] ) ? $_GET['page'] :'home';
                    switch ( $page ) {
                        case'danhmuc':
                            include 'customer/top_customers.php';
                            break;
                        case'sanpham':
                            include 'product/product_list.php';
                            break;
                        case'donhang':
                            require_once 'order/order_list.php';
                            break;
                        case'customer':
                            include 'customer/customer_listing.php';
                            break;
                        case 'phanquyen':
                            include 'phanquyen/phanquyen_customer.php';
                            break;                           
                        default:
                            include 'admin/system_dashboard.php';
                            break;
                    }
                ?>
                </div>
            </div>
        </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.ajax-link').click(function(e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];

$.ajax({
    url:'ajax_content.php',
    method: 'GET',
    data: { page: page },
    success: function(data) {
        $('#main-content').html(data);
        // Optionally update URL without reloading
        history.pushState(null, '', '?page=' + page);
    },
    error: function() {
        $('#main-content').html('<p>Đã xảy ra lỗi khi tải nội dung.</p>');
    }
});
    });

    // Handle browser back/forward buttons
    window.onpopstate = function() {
        var params = new URLSearchParams(window.location.search);
        var page = params.get('page') || 'home';
        var projectBasePath = window.location.pathname.split('/').slice(0,3).join('/'); // e.g. /1/Project_Web2
        $.ajax({
            url: projectBasePath + '/admin/ajax_content.php',
            method: 'GET',
            data: { page: page },
            success: function(data) {
                $('#main-content').html(data);
            },
            error: function() {
                $('#main-content').html('<p>Đã xảy ra lỗi khi tải nội dung.</p>');
            }
        });
    };
});
</script>
