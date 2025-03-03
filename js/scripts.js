var currentPage = 1; // Đưa ra ngoài để dùng chung
var totalPage = 0;

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
});

function LoadProducts(page) {
    $('#content__product').html("");

    $.ajax({
        type: "POST",
        url: "handle/log.php",
        dataType: "json",
        data: { page: page },
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
        
            $('#page').html("");
            for (let i = 1; i <= totalPage; ++i){
                $('#page').append(`<span onclick="LoadProducts(${i}) ">${i}</span>`);
            }
        },
        error: function (xhr, status, error) {
            console.error("Lỗi AJAX:", status, error);
        }
    });
}
