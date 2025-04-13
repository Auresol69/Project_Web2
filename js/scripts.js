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
  // Khởi tạo trạng thái ban đầu
  $("#overlay").hide();
  $("#open-form-log-in__dropdown").hide();

  // Load sản phẩm và giỏ hàng khi trang tải
  LoadProducts(currentPage);
  loadCart(false);

  // Sự kiện chọn loại sản phẩm từ header
  $(document).on("click", "#header__menu__sub div", function (event) {
    event.stopPropagation();
    type = $(this).data("tree_type");
    console.log(type);
    LoadProducts(1);
    $(".product-category").css("display", "none");
  });

  // Sự kiện chọn "Tất cả sản phẩm" từ header
  $(document).on("click", "#header__menu__product", function () {
    type = "all";
    console.log(type);
    LoadProducts(1);
  });

  // Sự kiện tăng số lượng trong giỏ hàng
  $(document).on("click", ".increase-qty", function () {
    let productId = $(this).closest(".cart-item").data("id");
    updateCart("increase", productId);
  });

  // Sự kiện giảm số lượng trong giỏ hàng
  $(document).on("click", ".decrease-qty", function () {
    let $quantityElement = $(this).siblings(".quantity-value");
    let currentQuantity = parseInt($quantityElement.text()) || 1;

    if (currentQuantity > 1) {
      let productId = $(this).closest(".cart-item").data("id");
      updateCart("decrease", productId);
    } else {
      alert("Số lượng tối thiểu là 1!");
    }
  });

  // Sự kiện xóa sản phẩm khỏi giỏ hàng
  $(document).on("click", ".remove-cart", function () {
    let productId = $(this).closest(".cart-item").data("id");
    if (confirm("Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?")) {
      updateCart("remove", productId);
    }
  });

  // Lọc sản phẩm từ danh mục
  $(document).on("click", ".category-card", function () {
    type = $(this).data("tree_type");
    console.log("Loại sản phẩm:", type);
    LoadProducts(1);
    $(".product-category").css("display", "none");
  });

  // Hiển thị dropdown khi rê chuột vào
  $("#open-form-log-in").on("mouseenter", function () {
    $.ajax({
        data: {action: "check"},
        type: "POST",
        url: "handle/auth.php",
        dataType: "json",
        success: function (response) {
            if (response.loggedIn) {
                $("#open-form-log-in__dropdown").show();
            } else {
                $("#overlay").show();
            }
        },
        error: function (xhr, status, error) {
            console.error("Lỗi AJAX (auth.php - mouseenter):", status, error);
            console.log("Phản hồi từ server:", xhr.responseText);
        },
    });
  });

  // Ẩn dropdown khi rê chuột ra ngoài
  $("#open-form-log-in").on("mouseleave", function () {
    $("#open-form-log-in__dropdown").hide();
  });

  // Xử lý nhấp vào "Đăng xuất"
  $("#open-form-log-in__logout").on("click", function (event) {
    event.preventDefault();
    $.ajax({
      data: {action: "logout"},
      type: "POST",
      url: "handle/auth.php",
      dataType: "json",
      success: function () {
        location.reload(true);
      },
      error: function () {
        console.error("Lỗi AJAX!");
      },
    });
  });

  // Xử lý nhấp vào "Thông tin khách hàng"
// Xử lý nhấp vào "Thông tin tài khoản"
$('#open-form-log-in__info').on('click', function (event) {
  event.preventDefault();

  // Kiểm tra trạng thái đăng nhập trước
  $.ajax({
      type: "POST",
      url: "handle/auth.php",
      data: { action: "check" },
      dataType: "json",
      success: function (response) {
          if (response.loggedIn) {
              // Nếu đã đăng nhập, gọi AJAX để lấy thông tin tài khoản
              $.ajax({
                  type: "GET",
                  url: "handle/customer_info.php",
                  dataType: "json",
                  success: function (response) {
                      if (response.status === "success") {
                          // Tạo HTML cho modal
                          let infoHtml = `
                              <div class="account-info-modal">
                                  <h2>Thông tin tài khoản</h2>
                                  <div><strong>Tên đăng nhập:</strong> ${response.data.username} (Không thể chỉnh sửa)</div>
                                  <div>
                                      <strong>Tên:</strong>
                                      <input type="text" id="name-input" value="${response.data.name}" placeholder="Nhập tên">
                                  </div>
                                  <div>
                                      <strong>Email:</strong>
                                      <input type="email" id="email-input" value="${response.data.email}" placeholder="Nhập email">
                                  </div>
                                  <div>
                                      <strong>Số điện thoại:</strong>
                                      <input type="text" id="phone-input" value="${response.data.phone}" placeholder="Nhập số điện thoại">
                                  </div>
                                  <div>
                                      <strong>Tỉnh/Thành phố:</strong>
                                      <select id="province-select">
                                          <option value="">-- Chọn tỉnh/thành phố --</option>
                                          ${response.provinces.map(province => `
                                              <option value="${province.province_id}" ${response.data.province_id === province.province_id ? 'selected' : ''}>
                                                  ${province.name}
                                              </option>
                                          `).join('')}
                                      </select>
                                  </div>
                                  <div>
                                      <strong>Quận/Huyện:</strong>
                                      <select id="district-select">
                                          <option value="">-- Chọn quận/huyện --</option>
                                      </select>
                                  </div>
                                  <div>
                                      <strong>Địa chỉ chi tiết:</strong>
                                      <input type="text" id="address-detail-input" value="${response.data.address_detail}" placeholder="Nhập địa chỉ chi tiết (tùy chọn)">
                                  </div>
                                  <button class="save-account">Lưu</button>
                                  <button class="close-modal">Đóng</button>
                              </div>
                          `;
                          $('body').append(infoHtml);

                          // Cập nhật danh sách quận/huyện dựa trên tỉnh/thành phố được chọn
                          function updateDistricts(selectedProvinceId) {
                              let districtSelect = $('#district-select');
                              districtSelect.html('<option value="">-- Chọn quận/huyện --</option>');
                              response.districts.forEach(district => {
                                  if (district.province_id === selectedProvinceId) {
                                      districtSelect.append(`
                                          <option value="${district.district_id}" ${response.data.district_id === district.district_id ? 'selected' : ''}>
                                              ${district.name}
                                          </option>
                                      `);
                                  }
                              });
                          }

                          // Gọi hàm cập nhật quận/huyện lần đầu tiên
                          if (response.data.province_id) {
                              updateDistricts(response.data.province_id);
                          }

                          // Xử lý sự kiện thay đổi tỉnh/thành phố
                          $('#province-select').on('change', function () {
                              let selectedProvinceId = $(this).val();
                              updateDistricts(selectedProvinceId);
                          });

                          // Xử lý sự kiện nút "Lưu"
                          $('.save-account').on('click', function () {
                            let newName = $('#name-input').val().trim();
                            let newEmail = $('#email-input').val().trim();
                            let newPhone = $('#phone-input').val().trim();
                            let newProvinceId = $('#province-select').val();
                            let newDistrictId = $('#district-select').val();
                            let newAddress = $('#address-detail-input').val().trim();

                            if (!newName || !newEmail || !newPhone) {
                                alert("Tên, email và số điện thoại không được để trống!");
                                return;
                            }

                            $.ajax({
                                type: "POST",
                                url: "handle/update_account.php",
                                data: {
                                    name: newName,
                                    email: newEmail,
                                    phone: newPhone,
                                    province_id: newProvinceId || null,
                                    district_id: newDistrictId || null,
                                    address_detail: newAddress || null
                                },
                                dataType: "json",
                                success: function (updateResponse) {
                                    if (updateResponse.status === "success") {
                                        alert(updateResponse.message);
                                        // Cập nhật lại giá trị hiển thị trên modal
                                        $('#name-input').val(newName);
                                        $('#email-input').val(newEmail);
                                        $('#phone-input').val(newPhone);
                                        $('#address-detail-input').val(newAddress);
                                        // Đóng modal với hiệu ứng fadeOut
                                        $('.account-info-modal').fadeOut(300, function() {
                                            $(this).remove();
                                        });
                                    } else {
                                        alert(updateResponse.message);
                                    }
                                },
                                error: function (xhr, status, error) {
                                    console.error("Lỗi AJAX (update_account.php):", status, error);
                                    console.log("Phản hồi từ server:", xhr.responseText);
                                    alert("Lỗi khi cập nhật thông tin tài khoản: " + error);
                                }
                            });
                          });

                          // Xử lý sự kiện nút "Đóng" (cũng áp dụng hiệu ứng fadeOut)
                          $('.close-modal').on('click', function () {
                            $('.account-info-modal').fadeOut(300, function() {
                                $(this).remove();
                            });
                          });

                          // Xử lý sự kiện nút "Đóng"
                          $('.close-modal').on('click', function () {
                              $('.account-info-modal').remove();
                          });
                      } else {
                          alert(response.message);
                      }
                  },
                  error: function (xhr, status, error) {
                      console.error("Lỗi AJAX (customer_info.php):", status, error);
                      console.log("Phản hồi từ server:", xhr.responseText);
                      if (xhr.status === 401) {
                          alert("Vui lòng đăng nhập để xem thông tin tài khoản!");
                          $('#overlay').show();
                      } else {
                          alert("Lỗi khi lấy thông tin tài khoản: " + error);
                      }
                  }
              });
          } else {
              $('#overlay').show();
              alert("Vui lòng đăng nhập để xem thông tin tài khoản!");
          }
      },
      error: function () {
          console.error("Lỗi kiểm tra trạng thái đăng nhập!");
          alert("Lỗi kiểm tra trạng thái đăng nhập!");
      }
  });
});

  // Đóng form đăng nhập khi nhấp ra ngoài
  $("#overlay").on("click", function (event) {
    if ($(event.target).closest(".Authentication-Form-Dad").length === 0) {
      $("#overlay").hide();
    }
  });

  // Sự kiện mở giỏ hàng
  $(document).on("click", ".cart-icon", function (e) {
    e.preventDefault();
    loadCart(true);
  });

  // Sự kiện cho nút "Mua tiếp"
  $(document).on("click", ".continue-shopping", function (e) {
    e.preventDefault();
    $("#modal-cart").css("display", "none");
    type = "all";
    LoadProducts(1);
  });

  // Sự kiện cho nút "Thanh toán"
  $(document).on("click", ".checkout", function () {
    $(this).text("Đang xử lý...").prop("disabled", true);

    if (
      $("#cart-items").children().length === 0 ||
      $("#cart-items").find(".empty-cart").length > 0
    ) {
      alert(
        "Giỏ hàng của bạn đang trống! Vui lòng thêm sản phẩm trước khi thanh toán."
      );
      $(this).text("Thanh toán").prop("disabled", false);
      return;
    }

    $.ajax({
      type: "POST",
      url: "handle/auth.php",
      data: {action: "check"},
      dataType: "json",
      success: function (response) {
        console.log("Phản hồi từ handle/auth.php:", response);
        if (response.loggedIn) {
          if ($("#payment-modal").length) {
            $("#payment-modal").css("display", "flex");
            $("#modal-cart").css("display", "none");
          } else {
            console.warn(
              "#payment-modal không tồn tại, chuyển hướng đến checkout."
            );
            window.location.href = "index.php?page=checkout";
          }
        } else {
          $("#overlay").show();
          alert("Vui lòng đăng nhập để thanh toán!");
        }
      },
      error: function (xhr, status, error) {
        console.error(
          "Lỗi AJAX khi kiểm tra đăng nhập:",
          status,
          error,
          xhr.responseText
        );
        alert("Lỗi kiểm tra trạng thái đăng nhập!");
      },
      complete: function () {
        $(".checkout").text("Thanh toán").prop("disabled", false);
      },
    });
  });

  // Sự kiện cho nút đóng modal
  $(document).on("click", ".close-modal", function () {
    $("#modal-cart").css("display", "none");
  });
  $.ajax({
    data: {action: "check"},
    type: "POST",
    url: "handle/auth.php",
    dataType: "json",
    success: function (response) {
        if (response.name !== undefined) {
            // Kiểm tra xem dòng "Xin chào" đã tồn tại chưa
            if ($(".greeting-message").length === 0) {
                $("#open-form-log-in").after(
                    '<div class="greeting-message" style="font-style: italic; max-width: 300px;">Xin chào, ' +
                    response.name +
                    "!</div>"
                );
            }
        }
    },
  });

  // Sự kiện cho nút "Thêm vào giỏ" trong danh sách sản phẩm
  $(document).on("click", ".btn-them", function () {
    let productId = $(this).data("id");
    let productName = $(this)
      .closest(".sanpham-card")
      .find(".sanpham-ten")
      .text();
    console.log("Thêm sản phẩm:", productId, productName);

    $.ajax({
      type: "POST",
      url: "handle/auth.php",
      data: {action: "check"},
      dataType: "json",
      success: function (response) {
        console.log("Trạng thái đăng nhập:", response);
        if (response.loggedIn) {
          updateCart(
            "add",
            productId,
            "Đã thêm " + productName + " vào giỏ hàng!"
          );
          loadCart(false);
        } else {
          $("#overlay").show();
          alert("Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!");
        }
      },
      error: function (xhr, status, error) {
        console.error(
          "Lỗi kiểm tra đăng nhập:",
          status,
          error,
          xhr.responseText
        );
        alert("Lỗi kiểm tra trạng thái đăng nhập!");
      },
    });
  });

  // Sự kiện chuyển đổi giữa đăng nhập và đăng ký
  $("#btn-mode").on("click", function (event) {
    event.preventDefault();
    var mode = $(this).data("mode");
    if (mode == "sign-up") {
      build("sign-up");
      $(this).data("mode", "sign-in");
      $("#lbl-mode").html("Nhập");
    } else {
      build("sign-in");
      $(this).data("mode", "sign-up");
      $("#lbl-mode").html("Ký");
    }
  });

  // Xử lý submit form đăng nhập/đăng ký
  $("#Authentication-Form").submit(function (event) {
    event.preventDefault();
    var data = $(this).serialize();
    var mode = $("#btn-mode").data("mode");
    $.ajax({
      type: "POST",
      url: "handle/login.php",
      data: data + "&mode=" + mode,
      dataType: "json",
      success: function (response) {
        if (response.status == "success") {
          $(".error-message").remove();
          alert(response.message);
          $("#Authentication-Form")[0].reset();
          $("#overlay").hide();
          location.reload(true);
        } else if (response.status == "error") {
          $(".error-message").remove();
          $.each(response.errors, function (key, message) {
            $("#" + key).after(
              '<div class="error-message" style="color:red;">' +
                message +
                "</div>"
            );
          });
        }
      },
    });
  });
});

function LoadProducts(page) {
  if ($("#keyword").val() !== null && $("#keyword").val() !== undefined)
    var keyword = $("#keyword").val().trim();

  if (
    $("#content__input__main__sort_min").val() != null &&
    $("#content__input__main__sort_min").val() !== undefined
  )
    var min = $("#content__input__main__sort_min").val().trim();
  if (
    $("#content__input__main__sort_max").val() != null &&
    $("#content__input__main__sort_max").val() !== undefined
  )
    var max = $("#content__input__main__sort_max").val().trim();

  $("#content__input__main__sort_type").on("change", function () {
    type = $(this).val().trim();
  });

  if (type && type !== "all") {
    $(".product-category").hide();
  } else {
    $(".product-category").show();
  }

  $.ajax({
    type: "POST",
    url: "handle/log.php",
    dataType: "json",
    data: {page: page, keyword: keyword, type: type, min: min, max: max},
    success: function (response) {
      console.log("Danh sách sản phẩm:", response.products);

      if (type && response.header__menu__sub) {
        const selectedType = response.header__menu__sub.find(
          (item) => item.typeid === type
        );
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
      } else {
        response.products.forEach((product) => {
          tmp += `
                        <div class="sanpham-card" data-id="${product.id}">
                            <div class="sanpham-img-container">
                                <img src="./${product.image}" alt="${
            product.name
          }" class="sanpham-img" loading="lazy">                                
                                <div class="overlay-xemnhanh" onclick='openModal(${JSON.stringify(
                                  product
                                )})'>Xem chi tiết</div>
                            </div>
                            <h3 class="sanpham-ten">${product.name}</h3>
                            <p class="sanpham-gia">${Number(
                              product.price
                            ).toLocaleString("vi-VN")}₫</p>
                            <button class="btn-them" data-id="${
                              product.id
                            }">Thêm vào giỏ</button>
                        </div>
                    `;
        });
      }
      $("#content__product").html(tmp);

      currentPage = response.page;
      totalPage = response.total;

      if (totalPage && totalPage > 1) {
        $("#page").html("");
        for (let i = 1; i <= totalPage; ++i) {
          $("#page").append(`<span onclick="LoadProducts(${i})">${i}</span>`);
        }
        $(".content__page").css("display", "flex");
      } else {
        $("#page").html("");
        $(".content__page").css("display", "none");
      }

      if (Array.isArray(response.header__menu__sub)) {
        $("#header__menu__sub").html("");
        response.header__menu__sub.forEach((type) => {
          $("#header__menu__sub").append(
            `<div data-tree_type="${type.typeid}">${type.type}</div>`
          );
        });
      }

      let selectedValue = $("#content__input__main__sort_type").val();
      $("#content__input__main__sort_type").html("");
      $("#content__input__main__sort_type").append(
        `<option value="all">All</option>`
      );
      response.header__menu__sub.forEach((type) => {
        $("#content__input__main__sort_type").append(
          `<option value="${type.typeid}">${type.type}</option>`
        );
      });

      if (selectedValue)
        $("#content__input__main__sort_type").val(selectedValue);
      else $("#content__input__main__sort_type").val("all");
    },
    error: function (xhr, status, error) {
      console.error("Lỗi AJAX:", status, error);
    },
  });
}

function updateCart(action, productId, successMessage = "") {
  console.log("Gửi yêu cầu updateCart:", {action, productId});
  $.post(
    "handle/cart.php",
    {action: action, productID: productId},
    function (response) {
      console.log("Phản hồi từ cart.php:", response);
      if (response.status === "success") {
        loadCart(false);
        if (successMessage) {
          alert(successMessage);
        }
      } else {
        alert("Lỗi: " + response.message);
      }
    },
    "json"
  ).fail(function (xhr, status, error) {
    console.error(
      "Lỗi AJAX trong updateCart:",
      status,
      error,
      xhr.responseText
    );
    alert(
      "Lỗi kết nối đến server! Chi tiết: " +
        status +
        " - " +
        error +
        " - Phản hồi: " +
        xhr.responseText
    );
  });
}

function loadCart(showModal = false) {
  if ($("#modal-cart").length === 0) {
    console.warn("Loading giohang.php dynamically.");
    $.get("giohang.php", function (data) {
      $("body").append(data);
      renderCart(showModal);
    }).fail(function (xhr, status, error) {
      console.error(
        "Failed to load giohang.php:",
        status,
        error,
        xhr.responseText
      );
      alert("Lỗi: Không thể tải giỏ hàng!");
    });
  } else {
    renderCart(showModal);
  }
}

function renderCart(showModal = false) {
  $.post(
    "handle/cart.php",
    {action: "get"},
    function (response) {
      console.log("Phản hồi từ loadCart:", response);

      let total = 0,
        totalQuantity = 0,
        cartHtml = "";

      if (!response.cart || response.cart.length === 0) {
        cartHtml = `<div class="empty-cart">
                            <i class="fa-solid fa-shopping-cart"></i>
                            <p>Giỏ hàng của bạn đang trống!</p>
                        </div>`;
        if ($("#cart-count").length) {
          $("#cart-count").text("").hide();
        }
      } else {
        response.cart.forEach((item) => {
          if (
            !item.ProductID ||
            !item.ProductName ||
            !item.Price ||
            !item.ProductImage ||
            !item.Quantity
          ) {
            console.error("Dữ liệu sản phẩm không đầy đủ:", item);
            return;
          }

          const price = parseFloat(item.Price) || 0;
          const quantity = parseInt(item.Quantity) || 0;
          total += price * quantity;
          totalQuantity += quantity;

          cartHtml += `<div class="cart-item" data-id="${item.ProductID}">
                    <img src="./${item.ProductImage}" alt="${
            item.ProductName
          }" width="50">
                    <div class="cart-info">
                        <span class="cart-name">${item.ProductName}</span>
                        <span class="cart-price">${price.toLocaleString(
                          "vi-VN"
                        )}đ</span>
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
        $(".total-price").text(total.toLocaleString("vi-VN") + "đ");
      }

      if (showModal) {
        $("#modal-cart").css("display", "block");
      }
    },
    "json"
  ).fail(function (xhr, status, error) {
    console.error("Lỗi AJAX trong loadCart:", status, error, xhr.responseText);
    alert("Lỗi khi tải giỏ hàng!");
  });
}

function build(mode) {
  $("#Authentication-Form").load("layout/login_layout.php?mode=" + mode);
}

// Modal chi tiết sản phẩm
function openModal(product) {
  document.getElementById("modal-img").src =
    "/treeshopuser/Project_Web2/" + product.image;
  document.getElementById("modal-title").textContent = product.name;
  document.getElementById("modal-code").textContent =
    "Mã sản phẩm: " + product.id;
  document.getElementById("modal-quantity").textContent =
    "Số lượng: " + product.soluong;
  document.getElementById("modal-price").textContent =
    Number(product.price).toLocaleString("vi-VN") + "₫";
  document.getElementById("modal-description").textContent =
    product.mota || "Chưa có mô tả";
  document.getElementById("productModal").style.display = "block";
}

function closeModal() {
  document.getElementById("productModal").style.display = "none";
}
