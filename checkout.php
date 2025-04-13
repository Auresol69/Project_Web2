<div id="payment-modal">
    <div class="payment-container">
        <div class="payment-col-left">
            <h2>THÔNG TIN NGƯỜI NHẬN</h2>
            <form action="" class="payment-form-left">
                <button class="close-payment-modal"><a href="index.php?page=sanpham">Thoát</a></button>
                <div class="payment-col-left-radio-btn">
                    <input type="radio" name="address_option" id="old_address" value="old">
                    <label for="old_address">Sử dụng địa chỉ từ tài khoản</label>
                    <span class="address-error" style="color: red; display: none; font-size: 14px; margin-left: 10px;">
                        (Địa chỉ không đầy đủ, vui lòng nhập địa chỉ mới)
                    </span>
                </div>
                <div class="payment-col-left-radio-btn">
                    <input type="radio" name="address_option" id="new_address" value="new">
                    <label for="new_address">Nhập địa chỉ giao hàng mới</label>
                </div>

                <div class="payment-form-group">
                    <label for="receiver_name">Tên người nhận: <span class="required">*</span></label>
                    <input type="text" id="receiver_name" name="receiver_name" required>
                </div>
                <div class="payment-form-group">
                    <label for="phone_number">Số điện thoại: <span class="required">*</span></label>
                    <input type="tel" id="phone_number" name="phone_number" required pattern="[0-9]{10,11}" title="Số điện thoại phải có 10-11 chữ số">
                </div>
                <div class="payment-form-group">
                    <label for="address_detail">Địa chỉ nhà: <span class="required">*</span></label>
                    <input type="text" id="address_detail" name="address_detail" required>
                </div>
                <div class="payment-form-group">
                    <label for="city">Thành phố: <span class="required">*</span></label>
                    <select id="city" name="city" required>
                        <option value="">-- Chọn tỉnh/thành phố --</option>
                    </select>
                </div>
                <div class="payment-form-group">
                    <label for="district">Quận/Huyện: <span class="required">*</span></label>
                    <select id="district" name="district" required>
                        <option value="">-- Chọn quận/huyện --</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="payment-col-right">
            <p class="payment-content-lable">ĐƠN HÀNG</p>
            <div class="bill-total" id="list-order-payment"></div>
            <div class="bill-payment">
                <div class="total-bill-order"></div>
                <div class="policy-note">
                    Bằng việc bấm vào nút “Đặt hàng”, tôi đồng ý với <a href="index.php?page=chinhsach" target="_blank">chính sách</a> của chúng tôi.
                </div>
                <div class="total-payment">
                    <div class="text">Tổng tiền</div>
                    <div class="price-bill">
                        <div class="price-final" id="payment-cart-price-final">0</div>
                    </div>
                </div>
                <button class="preview-checkout-btn" onclick="showPreviewOrder();">Xem tổng quát đơn hàng</button>
                <button class="complete-checkout-btn" onclick="placeOrder();">Đặt hàng</button>
            </div>
        </div>
    </div>
</div>