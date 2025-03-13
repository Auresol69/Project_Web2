var currentPage = 1; // Đưa ra ngoài để dùng chung
var totalPage = 0;
var type = "all";

function Lui() {
    if (currentPage > 1) {
        currentPage--;
        LoadProducts(currentPage);
    }
}

function Tien() {
    if (currentPage < totalPage) {
        currentPage++;
        LoadProducts(currentPage);
    }
}


$(document).ready(function () {
    LoadProducts(currentPage);

    $(document).on("click", "#header__menu__sub div", function (event) {

        // Tránh lan event lên phần tử cha
        event.stopPropagation();

        type = $(this).data("tree_type");
        console.log(type);
        LoadProducts(1);
    })

    $(document).on("click", "#header__menu__product", function () {
        type = "all";
        console.log(type);
        LoadProducts(1);
    });
});

function LoadProducts(page) {
    // Tìm tên sản phẩm
    var keyword = $("#keyword").val().trim();

    // Tìm sản phẩm trong khoảng giá
    var min = $("#content__input__main__sort_min").val().trim();
    var max = $("#content__input__main__sort_max").val().trim();

    // Tìm sản phẩm theo phân loại
    $("#content__input__main__sort_type").on("change", function () {
        type = $(this).val().trim();
    });

    // Lỗi chớp nháy do line code dưới
    // $('#content__product').html("");

    $.ajax({
        type: "POST",
        url: "handle/log.php",
        dataType: "json",
        data: { page: page, keyword: keyword, type: type, min: min, max: max },
        success: function (response) {
            var tmp = "";
            console.log(response);
            response.products.forEach(product => {
            tmp += `<div class="sanpham">
                    <img src="${product.image}" alt="">
                    <div class="product-id">Mã SP: ${product.id}</div>
                    <div class="product-name">Tên SP: ${product.name}</div>
                    <div class="product-price">Giá SP: ${product.price}</div>
                    <div>
                        <button class="buy-button">Mua</button>
                        <button class="detail-button">Chi tiết</button>
                    </div>
                </div>`;
            });
            $('#content__product').html(tmp);

            // Cập nhật currentPage và totalPage toàn cục
            currentPage = response.page;
            totalPage = response.total;

            console.log("Trang hiện tại:", currentPage, "Tổng số trang:", totalPage);

            // Hiển thị pagination
            $('#page').html("");
            for (let i = 1; i <= totalPage; ++i) {
                $('#page').append(`<span onclick="LoadProducts(${i})">${i}</span>`);
            }

            // Ẩn/hiện div chứa pagination
            if (!totalPage || totalPage <= 1) {
                $('.content__page').css('display', 'none');
            }
            else {
                $('.content__page').css('display', 'flex');
            }

            // Hiển thị danh mục sản phẩm
            $("#header__menu__sub").html("");
            response.header__menu__sub.forEach(type => {
                $('#header__menu__sub').append(`<div data-tree_type="${type.type}">${type.type}</div>`);
            });

            // Hiển thị phân loại sản phẩm

            // Mục đích giữ lại giá trị value đã chọn
            let selectedValue = $("#content__input__main__sort_type").val();

            $("#content__input__main__sort_type").html("");
            $("#content__input__main__sort_type").append(`<option value="all">All</option>`);
            response.header__menu__sub.forEach(type => {
                $("#content__input__main__sort_type").append(`<option value ="${type.type}">${type.type}</option>`);
            });

            // Chọn lại mục đã chọn
            if (selectedValue)
                $("#content__input__main__sort_type").val(selectedValue);
            else
                $("#content__input__main__sort_type").val("all");
        },
        error: function (xhr, status, error) {
            console.error("Lỗi AJAX:", status, error);
        }
    });
}





$(document).ready(function () {
    loadCart();

    // Sự kiện thêm sản phẩm vào giỏ hàng
    $(document).on("click", ".buy-button", function () {
        let productId = $(this).closest('.sanpham').find('.product-id').text().replace("Mã SP: ", "").trim();
        updateCart("add", productId, "Đã thêm vào giỏ hàng!");
    });

    // Sự kiện tăng số lượng
    $(document).on("click", ".increase-qty", function () {
        let productId = $(this).closest('.cart-item').data("id");
        updateCart("increase", productId);
    });

    // Sự kiện giảm số lượng
    $(document).on("click", ".decrease-qty", function () {
        let productId = $(this).closest('.cart-item').data("id");
        updateCart("decrease", productId);
    });

    // Sự kiện xóa sản phẩm khỏi giỏ hàng
    $(document).on("click", ".remove-cart", function () {
        let productId = $(this).closest('.cart-item').data("id");
        if (confirm("❗ Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?")) {
            updateCart("remove", productId, );
        }
    });

    // Hàm cập nhật giỏ hàng (Thêm, Xóa, Tăng, Giảm)
    function updateCart(action, productId, successMessage = "") {
        $.post("handle/cart.php", { action, productID: productId }, function (response) {
            if (response.status === "success") {
                loadCart();
                if (successMessage) alert(successMessage);
            } else {
                alert("Lỗi: " + response.message);
            }
        }, "json").fail(() => alert("Lỗi kết nối đến server!"));
    }

    // Hàm tải giỏ hàng từ database
    function loadCart() {
        $.post("handle/cart.php", { action: "get" }, function (response) {
            let total = 0, totalQuantity = 0, cartHtml = "";

            if (!response.cart || response.cart.length === 0) {
                cartHtml = `<div class="empty-cart">
                                <i class="fa-solid fa-shopping-cart"></i>
                                <p>Giỏ hàng của bạn đang trống!</p>
                            </div>`;
                $("#cart-count").text("").hide();
            } else {
                response.cart.forEach(item => {
                    total += parseFloat(item.Price) * parseInt(item.Quantity);
                    totalQuantity += parseInt(item.Quantity) || 0;

                    cartHtml += `<div class="cart-item" data-id="${item.ProductID}">
                        <img src="${item.ProductImage}" alt="${item.ProductName}" width="50">
                        <div class="cart-info">
                            <span class="cart-name">${item.ProductName}</span>
                            <span class="cart-price">${parseFloat(item.Price).toLocaleString('vi-VN')}đ</span>
                            <div class="cart-quantity">
                                <button class="decrease-qty">−</button>
                                <span class="quantity-value">${item.Quantity}</span>
                                <button class="increase-qty">+</button>
                            </div>
                        </div>
                        <button class="remove-cart"><i class="fa-solid fa-trash"></i></button>
                    </div>`;
                });

                totalQuantity = parseInt(totalQuantity) || 0;
                if (totalQuantity > 0) {
                    $("#cart-count").text(totalQuantity).show();
                } else {
                    $("#cart-count").text("").hide();
                }
            }

            $("#cart-items").html(cartHtml);
            $(".total-price").text(total.toLocaleString('vi-VN') + "đ");
            $(".button-cart").html(`
                <a href="index.php?page=sanpham" class="continue-shopping">🛍️ Mua tiếp</a>
                <a href="index.php?page=checkout" class="checkout">Thanh toán</a>
            `);
        }, "json").fail(() => alert("Lỗi khi tải giỏ hàng!"));
    }
});

