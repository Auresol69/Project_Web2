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
    loadCart();
    $(document).on("click", "#header__menu__sub div", function (event) { //chọn từ header

        // Tránh lan event lên phần tử cha
        event.stopPropagation();

        type = $(this).data("tree_type");
        console.log(type);
        LoadProducts(1);

        $('.product-category').css('display', 'none');
        
    });

    $(document).on("click", "#header__menu__product", function () { //chọn sản phẩm hiện tất cả
        type = "all";
        console.log(type);
        LoadProducts(1);
    });
    // Sự kiện tăng số lượng
    $(document).on("click", ".increase-qty", function () {
        let productId = $(this).closest('.cart-item').data("id");
        updateCart("increase", productId);
    });

    // Sự kiện giảm số lượng
    $(document).on("click", ".decrease-qty", function () {
        let $quantityElement = $(this).siblings(".quantity-value");
        let currentQuantity = parseInt($quantityElement.text()) || 1;

        if (currentQuantity > 1) {
            let productId = $(this).closest('.cart-item').data("id");
            updateCart("decrease", productId);
        } else {
            alert("Số lượng tối thiểu là 1!");
        }
    });

    // Sự kiện xóa sản phẩm khỏi giỏ hàng
    $(document).on("click", ".remove-cart", function () {
        let productId = $(this).closest('.cart-item').data("id");
        if (confirm("Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?")) {
            updateCart("remove", productId);
        }
    });

    // Thanh toán
    $(".checkout").on('click', function () {
        $.ajax({
            type: "POST",
            url: "handle/auth.php",
            data: { action: "check" },
            dataType: "json",
            success: function (response) {
                if (response.loggedIn == true) {
                    window.location.href = "index.php?page=checkout";
                } else {
                    $('#overlay').show();
                }
            },
            error: function () {
                alert("Lỗi kết nối đến server!");
            }
        });
    });
});

//lọc sản phẩm từ danh mục
$(document).on("click", ".category-card", function () {
    type = $(this).data("tree_type");
    console.log("Loại sản phẩm:", type);
    LoadProducts(1);

    $('.product-category').css('display', 'none');

});


function LoadProducts(page) {
    // Tìm tên sản phẩm
    if ($("#keyword").val() !== null && $("#keyword").val() !== undefined)
        var keyword = $("#keyword").val().trim();

    // Tìm sản phẩm trong khoảng giá
    if ($("#content__input__main__sort_min").val() != null && $("#content__input__main__sort_min").val() !== undefined)
        var min = $("#content__input__main__sort_min").val().trim();
    if ($("#content__input__main__sort_max").val() != null && $("#content__input__main__sort_max").val() !== undefined)
        var max = $("#content__input__main__sort_max").val().trim();

    // Tìm sản phẩm theo phân loại
    $("#content__input__main__sort_type").on("change", function () {
        type = $(this).val().trim();
    });

    // Nếu chọn loại cụ thể (không phải "all" hay "")
    if (type && type !== "all") {
        $(".product-category").hide(); // ẩn danh mục
    } else {
        $(".product-category").show(); // nếu chọn lại "Tất cả", hiện lại danh mục
    }

    // Lỗi chớp nháy do line code dưới
    // $('#content__product').html("");

    $.ajax({
        type: "POST",
        url: "handle/log.php",
        dataType: "json",
        data: { page: page, keyword: keyword, type: type, min: min, max: max },
        success: function (response) {
            console.log("Danh sách sản phẩm:", response.products);

            // Hiển thị tên loại sản phẩm trước
            if (type && response.header__menu__sub) {
                const selectedType = response.header__menu__sub.find(item => item.typeid === type);
                if (selectedType) {
                    $(".product-title").text(selectedType.type.toUpperCase());
                } else {
                $(".product-title").text("TẤT CẢ SẢN PHẨM");
                }
            }
            
            var tmp = "";
            
            if (!response.products || response.products.length === 0) {
                tmp = `<div class="empty-cart">
                                <i class="fa-solid fa-face-sad-cry"></i>
                                <p>Không có sản phẩm bạn cần!</p>
                            </div>`;
            }
            else {
                // Duyệt qua danh sách sản phẩm và tạo HTML
                response.products.forEach(product => {
                    tmp += `
                        <div class="sanpham-card" data-id="${product.id}">
                            <div class="sanpham-img-container">
                                <img src="./${product.image}" alt="${product.name}" class="sanpham-img" loading="lazy">                                
                                <div class="overlay-xemnhanh" onclick='openModal(${JSON.stringify(product)})'>Xem chi tiết</div>
                            </div>
                            <h3 class="sanpham-ten">${product.name}</h3>
                            <p class="sanpham-gia">${Number(product.price).toLocaleString("vi-VN")}₫</p>
                            <button class="btn-them" data-id="${product.id}">Thêm vào giỏ</button>
                        </div>
                    `;
                    
                });
            }
            $('#content__product').html(tmp);

            // Cập nhật currentPage và totalPage toàn cục
            currentPage = response.page;
            totalPage = response.total;
            
            // Hiển thị pagination
            if (totalPage && totalPage > 1) { //2 trang trở lên
                $('#page').html(""); // Xóa pagination cũ nếu có
                for (let i = 1; i <= totalPage; ++i) {
                    $('#page').append(`<span onclick="LoadProducts(${i})">${i}</span>`);
                }
                $('.content__page').css('display', 'flex'); // Hiện pagination
            } else {
                $('#page').html(""); // Xóa pagination nếu chỉ có 1 trang
                $('.content__page').css('display', 'none'); // Ẩn pagination
            }

            // Hiển thị danh mục sản phẩm
            if (Array.isArray(response.header__menu__sub)) {
                $('#header__menu__sub').html("");
                response.header__menu__sub.forEach(type => {
                    $('#header__menu__sub').append(`<div data-tree_type="${type.typeid}">${type.type}</div>`);
                });
            }
            

            // Hiển thị phân loại sản phẩm

            // Mục đích giữ lại giá trị value đã chọn
            let selectedValue = $("#content__input__main__sort_type").val();

            $("#content__input__main__sort_type").html("");
            $("#content__input__main__sort_type").append(`<option value="all">All</option>`);
            response.header__menu__sub.forEach(type => {
                $("#content__input__main__sort_type").append(`<option value ="${type.typeid}">${type.type}</option>`);
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

function updateCart(action, productId, successMessage = "") {
    console.log("Gửi yêu cầu updateCart:", { action, productId });
    $.post("handle/cart.php", { action: action, productID: productId }, function (response) {
        console.log("Phản hồi từ cart.php:", response);
        if (response.status === "success") {
            loadCart();
            if (successMessage) {
                alert(successMessage);
            }
        } else {
            alert("Lỗi: " + response.message);
        }
    }, "json").fail(function (xhr, status, error) {
        console.error("Lỗi AJAX trong updateCart:", status, error, xhr.responseText);
        alert("Lỗi kết nối đến server! Chi tiết: " + status + " - " + error + " - Phản hồi: " + xhr.responseText);
    });
}

function loadCart() {
    if ($("#modal-cart").length === 0) {
        console.warn("Loading giohang.php dynamically.");
        $.get('giohang.php', function(data) {
            $('body').append(data); // Append cart modal
            renderCart(); // Proceed with rendering
        }).fail(function() {
            console.error("Failed to load giohang.php");
            alert("Lỗi: Không thể tải giỏ hàng!");
        });
    } else {
        renderCart(); // Modal exists, render directly
    }
}

function renderCart() {
    $.post("handle/cart.php", { action: "get" }, function(response) {
        console.log("Phản hồi từ loadCart:", response);

        if ($("#modal-cart").length === 0) {
            console.error("#modal-cart không tồn tại trong DOM!");
            return;
        }

        if ($("#cart-items").length === 0) {
            console.error("#cart-items không tồn tại trong DOM!");
            alert("Lỗi: Không thể hiển thị giỏ hàng. Vui lòng thử lại sau!");
            return;
        }

        let total = 0, totalQuantity = 0, cartHtml = "";

        if (!response.cart || response.cart.length === 0) {
            cartHtml = `<div class="empty-cart">
                            <i class="fa-solid fa-shopping-cart"></i>
                            <p>Giỏ hàng của bạn đang trống!</p>
                        </div>`;
            if ($("#cart-count").length) {
                $("#cart-count").text("").hide();
            }
        } else {
            response.cart.forEach(item => {
                if (!item.ProductID || !item.ProductName || !item.Price || !item.ProductImage || !item.Quantity) {
                    console.error("Dữ liệu sản phẩm không đầy đủ:", item);
                    return;
                }

                const price = parseFloat(item.Price) || 0;
                const quantity = parseInt(item.Quantity) || 0;
                total += price * quantity;
                totalQuantity += quantity;

                cartHtml += `<div class="cart-item" data-id="${item.ProductID}">
                    <img src="./${item.ProductImage}" alt="${item.ProductName}" width="50">
                    <div class="cart-info">
                        <span class="cart-name">${item.ProductName}</span>
                        <span class="cart-price">${price.toLocaleString('vi-VN')}đ</span>
                        <div class="cart-quantity">
                            <button class="decrease-qty">−</button>
                            <span class="quantity-value">${quantity}</span>
                            <button class="increase-qty">+</button>
                        </div>
                    </div>
                    <button class="remove-cart"><i class="fa-solid fa-trash"></i></button>
                </div>`;
            });

            if (totalQuantity > 0 && $("#cart-count").length) {
                $("#cart-count").text(totalQuantity).show();
            }
        }

        $("#cart-items").html(cartHtml);
        if ($(".total-price").length) {
            $(".total-price").text(total.toLocaleString('vi-VN') + "đ");
        }

        $("#modal-cart").css("display", "block");
    }, "json").fail(function(xhr, status, error) {
        console.error("Lỗi AJAX trong loadCart:", status, error, xhr.responseText);
        alert("Lỗi khi tải giỏ hàng!");
    });
}
// Sự kiện thêm sản phẩm vào giỏ hàng từ danh sách sản phẩm
$(document).on("click", ".btn-them", function () {
    $.ajax({
        type: "POST",
        url: "handle/auth.php",
        data: { action: "check" },
        dataType: "json",
        success: function (response) {
            if (response.loggedIn) {
                let productCard = $(this).closest('.sanpham-card');
                let productId = productCard.data('id');
                let productName = productCard.find('.sanpham-ten').text();
                updateCart("add", productId, "Đã thêm " + productName + " vào giỏ hàng!");
            } else {
                $('#overlay').show(); 
                alert("Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!");
            }
        }.bind(this),
        error: function () {
            alert("Lỗi kiểm tra trạng thái đăng nhập!");
        }
    });
});

$(document).on("click", ".cart-icon-sp", function () {
    $.ajax({
        type: "POST",
        url: "handle/auth.php",
        data: { action: "check" },
        dataType: "json",
        success: function (response) {
            if (response.loggedIn) {
                let productId = $('#modal-code').text().replace("Mã sản phẩm: ", "").trim();
                let productName = $('#modal-title').text();
                updateCart("add", productId, "Đã thêm " + productName + " vào giỏ hàng!");
            } else {
                $('#overlay').show(); 
                alert("Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!");
            }
        },
        error: function () {
            alert("Lỗi kiểm tra trạng thái đăng nhập!");
        }
    });
});

// Thanh toán


// Login/Logout
$(document).ready(function () {
    $.ajax({
        data: { action: "check" },
        type: "POST",
        url: "handle/auth.php",
        dataType: "json",
        success: function (response) {
            if (response.name !== undefined)
                $('#open-form-log-in').after('<div style="font-style: italic; max-width: 300px;">Xin chào, ' + response.name + '!</div>');
        }
    });
});

$(document).ready(function () {
    $('#overlay').hide();
    $('#open-form-log-in__logout').hide();

    $('#open-form-log-in').on('click', function (event) {
        event.preventDefault();
        $.ajax({
            data: { action: "check" },
            type: "POST",
            url: "handle/auth.php",
            dataType: "json",
            success: function (response) {
                if (response.loggedIn) {
                    $('#open-form-log-in__logout').show();
                }
                else {
                    $('#overlay').show();
                }
            },
            error: function () {
                console.error("Lỗi AJAX!");
            }
        });
    });

    $('#overlay').on('click', function (event) {
        if ($(event.target).closest('.Authentication-Form-Dad').length === 0) {
            $('#overlay').hide();
        }
    });
});

$(document).ready(function () {
    $('#open-form-log-in__logout').on('click', function (event) {
        event.preventDefault();
        $.ajax({
            data: { action: 'logout' },
            type: "POST",
            url: "handle/auth.php",
            dataType: "json",
            success: function () {
                location.reload(true);
            },
            error: function () {
                console.error("Lỗi AJAX!");
            }
        });
    });
    $(document).on('click', function (event) {
        if ($(event.target).closest('#open-form-log-in__logout').length === 0) {
            $('#open-form-log-in__logout').hide();
        }
    });
});

function build(mode) {
    $('#Authentication-Form').load('layout/login_layout.php?mode=' + mode);
}

$('#btn-mode').on('click', function (event) {
    event.preventDefault();
    var mode = $(this).data('mode');
    if (mode == 'sign-up') {
        build('sign-up');
        $(this).data('mode', 'sign-in');
        $('#lbl-mode').html('Nhập');
    }
    else {
        build('sign-in');
        $(this).data('mode', 'sign-up');
        $('#lbl-mode').html('Ký');
    }
});

$(document).ready(function () {
    $('#Authentication-Form').submit(function (event) {
        event.preventDefault();
        var data = $(this).serialize();
        var mode = $('#btn-mode').data('mode');
        $.ajax({
            type: "POST",
            url: "handle/login.php",
            data: data + '&mode=' + mode,
            dataType: "json",
            success: function (response) {
                if (response.status == "success") {
                    $('.error-message').remove();
                    alert(response.message);
                    $('#Authentication-Form')[0].reset();
                    if (response.name !== undefined)
                        $('#open-form-log-in').after('<div style="font-style: italic;">Xin chào, ' + response.name + '!</div>');
                    $('#overlay').hide();
                }
                else if (response.status == "error") {
                    $('.error-message').remove();
                    $.each(response.errors, function (key, message) {
                        $("#" + key).after('<div class="error-message" style="color:red;">' + message + '</div>');
                    });
                }
            }
        });
    });
});

//Modal chi tiết sản phẩm
function openModal(product) {
    document.getElementById("modal-img").src = "/treeshopuser/Project_Web2/" + product.image;
    document.getElementById("modal-title").textContent = product.name;
    document.getElementById("modal-code").textContent = "Mã sản phẩm: " + product.id;
    document.getElementById("modal-quantity").textContent = "Số lượng: " + product.soluong;
    document.getElementById("modal-price").textContent = Number(product.price).toLocaleString("vi-VN") + "₫";
    document.getElementById("modal-description").textContent = product.mota || "Chưa có mô tả";

    document.getElementById("productModal").style.display = "block";
}

function closeModal() {
    document.getElementById("productModal").style.display = "none";
}
