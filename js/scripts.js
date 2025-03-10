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
                            <div>Mã SP: ${product.id}</div>
                            <div>Tên SP: ${product.name}</div>
                            <div>Giá SP: ${product.price}</div>
                            <div>
                                <span>Mua</span>
                                <span>Chi tiết</span>
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
            $("#content__input__main__sort_type").val(selectedValue);
        },
        error: function (xhr, status, error) {
            console.error("Lỗi AJAX:", status, error);
        }
    });
}
