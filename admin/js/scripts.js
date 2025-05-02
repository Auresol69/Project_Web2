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
});