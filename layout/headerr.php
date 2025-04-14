<div class="header">
    <div class="header__name">Green Haven</div>
    <div class="header__menu">
        <div><a href="index.php?page=trangchu">TRANG CHỦ</a></div>
        <div id="header__menu__product">
            <a href="index.php?page=sanpham">SẢN PHẨM</a>
            <div id="header__menu__sub"></div>
        </div>
        <div><a href="index.php?page=chinhsach">CHÍNH SÁCH</a></div>
    </div>
    <div class="header__icons">

        <div class="live-search-wrapper">
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Tìm sản phẩm..." onkeyup="liveSearch(this.value)">
                <i class="fa-solid fa-magnifying-glass"></i>
                <div class="search-result" id="searchResult"></div>
            </div>

        </div>

        <a href="index.php?page=giohang" class="cart-icon">
            <i class="fa-solid fa-cart-shopping"></i>
            <span id="cart-count">0</span>
        </a>
        <div id="open-form-log-in">
            <i class="fa-solid fa-user"></i>
            <div id="open-form-log-in__logout">Đăng xuất</div>
        </div>
        <i class="fa-solid fa-bars"></i>
    </div>
</div>