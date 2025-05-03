$(document).ready(function () {
    // Khởi tạo trạng thái ban đầu
    $("#overlay").hide();
    $("#open-form-log-in__dropdown").hide();

    $("#open-form-log-in").on("click", function () {
        $.ajax({
            data: { action: "check" },
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
                console.error("Lỗi AJAX (auth.php - click):", status, error);
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
            data: { action: "logout" },
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

    // Đóng form đăng nhập khi nhấp ra ngoài
    $("#overlay").on("click", function (event) {
        if ($(event.target).closest(".Authentication-Form-Dad").length === 0) {
            $("#overlay").hide();
        }
    });

    $.ajax({
        data: { action: "check" },
        type: "POST",
        url: "handle/auth.php",
        dataType: "json",
        success: function (response) {
            if (response.staffname !== undefined) {
                $(".left-panel").eq(0).append(
                    '<div class="greeting-message" style="font-style: italic; max-width: 300px;">Xin chào, ' +
                    response.staffname +
                    "!</div>"
                );
            }
        },
    });

    // Xử lý submit form đăng nhập
    $("#Authentication-Form").submit(function (event) {
        event.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "handle/login.php",
            data: data,
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

    function checkPowerGroup() {
        setTimeout(function () {
            $.ajax({
                type: "POST",
                url: "handle/auth.php",
                data: { action: "check_powergroup" },
                dataType: "json",
                success: function (response) {
                    // Hàm lấy thông tin page từ URL
                    function getPageFromURL() {
                        const UrlParams = new URLSearchParams(window.location.search);
                        return UrlParams.get("page")?.toUpperCase();
                    }

                    let currentModule = getPageFromURL();
                    console.log(currentModule + " " + response[currentModule]);

                    // 1. Ẩn toàn bộ menu trước
                    $(".ajax-link").each(function () {
                        const page = $(this).attr("href").split("page=")[1]?.toUpperCase();

                        if (page === "HOME") {
                            $(this).show();
                            return; // bỏ qua xử lý tiếp theo
                        }

                        if (response[page] && response[page].includes("xem")) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });

                    if (response[currentModule]) {
                        // Hiển thị module nếu có quyền
                        $("." + currentModule).show();

                        // Ẩn tất cả các nút hành động trước khi hiển thị chúng dựa trên quyền của module hiện tại
                        $(".permission-sua, .permission-xoa, .permission-them").hide();

                        // Lặp qua quyền của module hiện tại để hiển thị các nút hành động
                        response[currentModule].forEach(function (action) {
                            // Kiểm tra quyền cho các hành động như "sua", "xoa", "them"
                            if (action === "sua") {
                                $(".permission-sua").show();
                            } else if (action === "xoa") {
                                $(".permission-xoa").show();
                            } else if (action === "them") {
                                $(".permission-them").show();
                            }
                        });
                    } else {
                        // Nếu module hiện tại không có quyền nào, ẩn tất cả các nút hành động
                        $(".permission-sua, .permission-xoa, .permission-them").hide();
                    }
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 401) {
                        // Xử lý khi chưa đăng nhập
                        $("body").html(`
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; font-family: sans-serif;">
                                <div style="font-size: 100px;">🔒</div>
                                <div style="font-size: 24px; color: red;">Bạn chưa đăng nhập</div>
                                <button id="login-btn" style="margin-top: 20px; padding: 10px 20px; font-size: 18px;">Đăng nhập ngay</button>
                            </div>
                        `);
                    } else {
                        console.error("Lỗi khác:", xhr);
                    }
                },
            });
        }, 50);
    }

    checkPowerGroup();

    $(".ajax-link").on("click", function () {
        checkPowerGroup();
    });

});