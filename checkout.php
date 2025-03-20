<div id="payment-modal">
        <div class="payment-container">
            <div class="payment-col-left">
                <h2>THÔNG TIN NGƯỜI NHẬN</h2>
                <form action="" class="payment-form-left">
                    <div class="payment-col-left-radio-btn">
                        <input type="radio" name="address_option" id="old_address" checked>
                        <label for="old_address">Sử dụng địa chỉ từ tài khoản</label>
                    </div>
                    <div class="payment-col-left-radio-btn">
                        <input type="radio" name="address_option" id="new_address">
                        <label for="new_address">Nhập địa chỉ giao hàng mới</label>
                    </div>

                    <div class="payment-form-group">
                        <label for="receiver_name">Tên người nhận:</label>
                        <input type="text" id="receiver_name" >
                    </div>
                    <div class="payment-form-group">
                        <label for="phone_number">Số điện thoại:</label>
                        <input type="tel" id="phone_number">
                    </div>
                    <div class="payment-form-group">
                        <label for="address_detail">Địa chỉ nhà:</label>
                        <input type="text" id="address_detail" >
                    </div>
                    <div class="payment-form-group">
                        <label for="city">Thành phố:</label>
                        <select id="city" name="city">

                        </select>
                    </div>
                    <div class="payment-form-group">
                        <label for="district">Quận:</label>
                        <select id="district" name="district">

                        </select>
                    </div>
                </form>
            </div>
            <div class="payment-col-right">
                <p class="payment-content-lable">ĐƠN HÀNG</p>
                <div class="bill-total" id="list-order-payment"></div>
                <div class="bill-payment">
                    <div class="total-bill-order">
                    </div>
                    <div class="policy-note">
                        Bằng việc bấm vào nút “Đặt hàng”, tôi đồng ý với <a href="index.php?page=chinhsach" target="_blank"> chính sách</a>của chúng tôi.
                    </div>
                    <div class="total-payment">
                        <div class="text">Tổng tiền</div>
                        <div class="price-bill">
                            <div class="price-final" id="payment-cart-price-final">0</div>
                        </div>
                    </div>
                    <button class="preview-checkout-btn" onclick=showPreviewOrder();>Xem tổng quát đơn hàng</button>
                    <button class="complete-checkout-btn">Đặt hàng</button>
                </div>
            </div>
        </div>
    </div>
