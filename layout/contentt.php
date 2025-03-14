<div class="content">
    <div class="content__input">
        <h1>CÂY TRỒNG<br> DÀNH CHO BẠN     
        </h1>
        <div class="content__input-img">
            <img src="img/cay.png" alt="">
        </div>
        <p class="content__slogan">Khám phá thế giới cây xanh, làm đẹp không gian sống!</p>

        <div class="content__input__main">
            <input id="keyword" type="text" placeholder="Tìm kiếm">
            <i class="fa-solid fa-magnifying-glass" onclick="LoadProducts(1)"></i>
        </div>
        <div class="content__input__sort">
            <input id="content__input__main__sort_min" type="text" placeholder="min">
            <input id="content__input__main__sort_max" type="text" placeholder="max">
            <select id="content__input__main__sort_type">
            </select>
        </div>
    </div>
    <div id="content__product"></div>
    <div class="content__page">
        <div class="prev" onclick="Lui()">
            < </div>
                <div id="page"> </div>
                <div class="next" onclick="Tien()"> > </div>
        </div>
    </div>
</div>