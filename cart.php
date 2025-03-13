
<div class="modal-cart"><!--Giỏ hàng-->
                <div class="cart-container">
                    <div class="khach-hang"><!--cách thức thanh toán-->
                        <form>
                            <div class="form-group">
                                <label for="phuong-thuc-thanh-toan">Phương Thức Thanh Toán:</label>
                                <select id="phuong-thuc-thanh-toan" style="width: 100%; padding: 10px; font-size: 1em; border-radius: 5px; border: 1px #ccc solid" onchange="hienThiCongThanhToan()">
                                    <option value="tien-mat">Tiền mặt</option>
                                    <option value="chuyen-khoan">Chuyển khoản</option>
                                    <option value="the">Thanh toán qua thẻ</option>
                                </select>
                            </div>
                            <div id="cong-thanh-toan" class="cong-thanh-toan hidden"><!--dùng thẻ-->
                                <h3>Thông Tin Thẻ</h3>
                                <div class="form-group">
                                    <label for="so-the">Số Thẻ:</label>
                                    <input type="text" id="so-the" placeholder="Nhập số thẻ" maxlength="16" required />
                                </div>
                                <div class="form-group">
                                    <label for="ngay-het-han">Ngày Hết Hạn:</label>
                                    <input type="month" id="ngay-het-han" required />
                                </div>
                                <div class="form-group">
                                    <label for="cvv">CVV:</label>
                                    <input type="password" id="cvv" placeholder="Nhập CVV" maxlength="3" required />
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="cart-header">
                        <h3 class="cart-header-title"><i class="fa fa-shopping-cart" style="color:black"></i>Giỏ hàng</h3>
                        <a href="index.php?page=sanpham" style="text-decoration: none"><button class="cart-close" onclick="closeCart()"><i class="fa fa-times"></i></button></a>
                    </div>
                    <div class="cart-body">
                        <div class="gio-hang-trong">
                            <i class="fa fa-shopping-basket" ></i>
                            <p>Không có sản phẩm nào trong giỏ hàng của bạn</p>
                        </div>
                        <ul class="cart-list?"></ul>
                        
                    </div>
                    <div class="cart-footer">
                        <div class="cart-total-price">
                            <p class="text-tt">Tổng tiền:</p>
                            <p class="text-price">0đ</p>
                        </div>
                        <div class="cart-footer-payment">
                            <a href="index.php?page=sanpham"><button class="them-sanpham"> Mua tiếp</button></a>
                            <a href="index.php?page=thanhtoan"><button class="thanh-toan">Thanh toán</button></a>
                        </div>
                    </div>
                </div>
            </div>

        <div class="checkout-page">
            <div class="checkout-header">
                <div class="checkout-return">
                    <button onclick="closecheckout()"><i class="fa fa-chevron-left"></i></button>
                </div>
                <h2 class="checkout-title">Thanh toán</h2>
            </div>