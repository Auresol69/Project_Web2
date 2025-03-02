$(document).ready(function () {
    $('#content__product').html("");


    $.ajax({
        type: "POST",
        url: "handle/log.php",
        dataType: "json",
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
        }, error: function (xhr, status, error) {
            console.error("Lỗi AJAX:", status, error);
        }
    });
});
