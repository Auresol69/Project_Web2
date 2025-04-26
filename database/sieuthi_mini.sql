


-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2025 at 08:20 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `treeshop1`
--

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--
CREATE TABLE provinces (
    `province_id` VARCHAR(10) PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL
);

CREATE TABLE districts (
    `district_id` VARCHAR(10) PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `province_id` VARCHAR(10),
    FOREIGN KEY (province_id) REFERENCES provinces(province_id)
);




INSERT INTO provinces (province_id, name) VALUES 
('01', 'TP. Hồ Chí Minh'),
('02', 'TP. Đà Nẵng'),
('03', 'TP. Hà Nội');



INSERT INTO districts (district_id, name, province_id) VALUES 
-- Quận/Huyện thuộc TP. Hồ Chí Minh (province_id = '01')
('001', 'Quận 1', '01'),
('002', 'Quận 3', '01'),
('003', 'Quận 7', '01'),
('004', 'Quận 10', '01'),
('005', 'Quận 5', '01'),
('006', 'Huyện Bình Chánh', '01'),
('007', 'Huyện Nhà Bè', '01'),
('008', 'Huyện Cần Giờ', '01'),
-- Quận/Huyện thuộc Đà Nẵng (province_id = '02')
('009', 'Quận Hải Châu', '02'),
('010', 'Quận Thanh Khê', '02'),
('011', 'Quận Liên Chiểu', '02'),
('012', 'Huyện Hòa Vang', '02'),
-- Quận/Huyện thuộc Hà Nội (province_id = '03')
('013', 'Quận Ba Đình', '03'),
('014', 'Quận Hoàn Kiếm', '03'),
('015', 'Quận Đống Đa', '03'),
('016', 'Huyện Đông Anh', '03');


CREATE TABLE `bill` (
  `mabill` varchar(20) NOT NULL,
  `macustomer` varchar(20) NOT NULL,
  `maorder` varchar(20) NOT NULL,
  `mapayby` varchar(20) NOT NULL,
  `ngaymua` datetime(6) DEFAULT current_timestamp(6),
  `tongtien` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `magiohang` varchar(20) NOT NULL,
  `mauser` varchar(20) NOT NULL,
  `maorder` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Table structure for table `product_cart`
--

CREATE TABLE `product_cart` (
  `masp` varchar(20) NOT NULL,
  `magiohang` varchar(20) NOT NULL,
  `soluong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `cart`
--
DELIMITER $$
CREATE TRIGGER `after_update_cart` AFTER UPDATE ON `cart` FOR EACH ROW BEGIN
    -- Kiểm tra nếu maorder được cập nhật từ NULL thành một giá trị hợp lệ
    IF OLD.maorder IS NULL AND NEW.maorder IS NOT NULL THEN
        -- Cập nhật số lượng sản phẩm trong bảng product
        UPDATE product p
        JOIN product_cart pc ON p.masp = pc.masp
        SET p.soluong = p.soluong - pc.soluong
        WHERE pc.magiohang = NEW.magiohang;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `macomment` int(11) NOT NULL,
  `noidung` text NOT NULL,
  `ngaydang` datetime(6) DEFAULT current_timestamp(6),
  `masp` varchar(20) NOT NULL,
  `mauser` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
    `macustomer` varchar(20) NOT NULL,
    `username` varchar(25) NOT NULL,
    `password` varchar(255) NOT NULL,
    `address` varchar(100) DEFAULT NULL,
    `phone` varchar(11) DEFAULT NULL,
    `name` varchar(25) DEFAULT NULL,
    `email` varchar(255) DEFAULT NULL,
    `province_id` VARCHAR(10) DEFAULT NULL, 
    `district_id` VARCHAR(10) DEFAULT NULL,
    `address_detail` TEXT DEFAULT NULL,
    PRIMARY KEY (`macustomer`),
    CONSTRAINT `fk_customer_province` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`province_id`) ON DELETE SET NULL,
    CONSTRAINT `fk_customer_district` FOREIGN KEY (`district_id`) REFERENCES `districts` (`district_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


CREATE TABLE `bill_details` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `mabill` VARCHAR(20),
    `masp` VARCHAR(20),
    `tensp` VARCHAR(255),
    `soluong` INT,
    `dongia` INT,
    `thanhtien` INT,
    FOREIGN KEY (mabill) REFERENCES bill(mabill),
    FOREIGN KEY (masp) REFERENCES product(masp)
);

--
-- Triggers `customer`
--
DELIMITER $$
CREATE TRIGGER `before_insert_customer` BEFORE INSERT ON `customer` FOR EACH ROW BEGIN
    DECLARE new_id INT;
    SELECT COALESCE(MAX(CAST(SUBSTRING(macustomer,4) AS UNSIGNED)),0) + 1 INTO new_id FROM customer;
    SET NEW.macustomer = CONCAT('CUS', LPAD(CAST(new_id AS CHAR),3,'0')); 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `detail_entry_form`
--

CREATE TABLE `detail_entry_form` (
  `maphieunhap` varchar(20) NOT NULL,
  `masp` varchar(20) NOT NULL,
  `dongianhap` int(11) NOT NULL,
  `soluongnhap` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `detail_entry_form`
--
DELIMITER $$
CREATE TRIGGER `after_insert_detail_entry_form` AFTER INSERT ON `detail_entry_form` FOR EACH ROW BEGIN
    UPDATE product SET soluong = soluong + NEW.soluongnhap, dongiasanpham = NEW.dongianhap where masp = NEW.masp;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `entry_form`
--

CREATE TABLE `entry_form` (
  `maphieunhap` varchar(20) NOT NULL,
  `ngaynhap` datetime(6) DEFAULT NULL,
  `mancc` varchar(20) NOT NULL,
  `mastaff` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Triggers `entry_form`
--
DELIMITER $$
CREATE TRIGGER `before_insert_entry_form` BEFORE INSERT ON `entry_form` FOR EACH ROW BEGIN
    DECLARE new_id INT;
    SELECT COALESCE(MAX(CAST(SUBSTRING(maphieunhap,4) AS UNSIGNED)),0) + 1 INTO new_id FROM entry_form;
    SET NEW.maphieunhap = CONCAT('EFO', LPAD(CAST(new_id AS CHAR),3,'0')); 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `func`
--

CREATE TABLE `func` (
  `funcid` varchar(20) NOT NULL,
  `funcname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `maorder` varchar(20) NOT NULL,
  `magiohang` varchar(20) NOT NULL,
  `mauser` varchar(20) NOT NULL,
  `mabill` varchar(20) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1 CHECK (`status` in (1,2))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payby`
--

CREATE TABLE `payby` (
  `mapayby` varchar(20) NOT NULL,
  `paybyname` varchar(50) NOT NULL,
  `address` varchar(100) NOT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`details`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `powergroup`
--

CREATE TABLE `powergroup` (
  `powergroupid` varchar(20) NOT NULL,
  `powergroupname` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `powergroup_func`
--

CREATE TABLE `powergroup_func` (
  `powergroupid` varchar(20) NOT NULL,
  `funcid` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `masp` varchar(20) NOT NULL,
  `tensp` varchar(50) NOT NULL,
  `soluong` int(11) NOT NULL DEFAULT 0,
  `dongiasanpham` int(11) DEFAULT NULL,
  `maloaisp` varchar(20) NOT NULL,
  `mancc` varchar(20) NOT NULL,
  `img` varchar(100) NOT NULL DEFAULT 'img/Blank.png',
  `mota` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`masp`, `tensp`, `soluong`, `dongiasanpham`, `maloaisp`, `mancc`, `img`, `mota`) VALUES
('PRO001', 'Cây cà phê', 100, 150000, 'TYP001', 'SUP001', 'img/caycafe.jpg', 'Cafe cũng là dòng cây trồng trong nhà mới được yêu thích trong thời gian gần đây. Trái ngược với những cây cafe công nghiệp phục vụ mục đích thu hoạch có kích thước lớn, dòng cây dùng làm cảnh có thể đặt vừa trong một chiếc ly. Lá cây xanh đậm, dày dặn, tạo cảm giác rất xanh tốt. Vào khoảng tháng 11 đến tháng 4 là mùa cafe ra hoa. Nếu chăm sóc tốt, bạn có thể ngắm loài hoa cafe trắng muốt với hương thơm toả nhẹ vô cùng dễ chịu.'),
('PRO002', 'Cây cam họ quýt', 150, 150000, 'TYP006', 'SUP002', 'img/caycamquytchanh.jpg', 'Không chỉ mang yếu tố may mắn theo quan niệm Á đông, các dòng cây cam quýt còn đẹp mắt và dễ trồng; rất thích hợp làm cây trồng trong nhà. Cây cam quýt có mùi thơm nhẹ nhàng và dễ chịu, giúp không gian nhà luôn sảng khoái, tươi mới. Trong ẩm thực Việt, lá cây cũng rất hữu ích. Đây là loại cây được các bà các mẹ ưa chuộng trồng từ lâu trong đời sống người Việt.'),
('PRO003', 'Cây cọ cảnh', 200, 150000, 'TYP002', 'SUP003', 'img/caycocanh.jpg', 'Nếu bạn tìm loại cây cao để đặt ở loggia, góc phòng, chân cầu thang,…thì cọ cảnh sẽ là lựa chọn số 1. Loài cây này mang vẻ đẹp đặc trưng của xứ nhiệt đới với lá nhọn và cành dài. Cọ cảnh thích hợp cho mọi phong cách nội thất, đặc biệt là phong cách tối giản và Scandinavia.'),
('PRO004', 'Cây dây nhện', 180, 150000, 'TYP001', 'SUP004', 'img/caydaynhen.jpg', 'Tuy có cái tên không được “lãng mạn” nhưng cây dây nhện lại có vẻ đẹp khá ấn tượng với những lá dài màu gân lá nổi bật. Cây dây nhện có khả năng quang hợp mạnh mẽ trong tình trạng ánh sáng tối thiểu. Bởi vậy, đây là loại cây rất thích hợp trồng trong nhà. Bên cạnh đó, khả năng lọc không khí của cây được đánh giá cao. Một chậu cây cỡ trung bình là đủ cho căn phòng 200m2.'),
('PRO005', 'Cây dương xỉ', 120, 150000, 'TYP001', 'SUP005', 'img/cayduongxi.jpg', 'Những năm gần đây, xương xỉ đã dần trở thành một loại cây cảnh được ưa thích. Vốn là loại cây mọc dại, dương xỉ không cần đất hay nhiều ánh sáng. Chỉ cần có độ ẩm đủ cao là cây có thể phát triển xanh tốt. Đặc biệt, đây là loại cây có kích thước khá nhỏ, thích hợp để bàn làm việc. Dương xỉ cũng nằm trong nhóm những loại cây có chức năng lọc độc trong không khí. Điển hình nhất là loại bỏ thuỷ ngân và asen.'),
('PRO006', 'Cây lan ý', 250, 150000, 'TYP005', 'SUP006', 'img/caylany.jpg', 'Lan ý là một trong những dòng cây ra hoa hiếm hoi làm cây trồng trong nhà. Hoa lan ý có màu trắng, căng phồng lên tựa như một cánh buồm. Lá cây xanh mướt rất đẹp mắt. Đây còn là dòng cây có khả năng lọc khí độc cực tốt, giúp loại bỏ formaldehyde, benzen và trichloroethylene, CO2, ammoniac,…có trong không khí nhà bạn.'),
('PRO007', 'Cây sim', 140, 150000, 'TYP003', 'SUP007', 'img/caysim.jpg', NULL),
('PRO008', 'Cây thường xuân', 160, 150000, 'TYP002', 'SUP008', 'img/caythuongxuan.jpg', 'Được mệnh danh là cỗ máy lọc không khí hoàn hảo. Trong 06 tiếng, cây sẽ loại bỏ 58% phân tử nấm mốc và 60% các chất độc xung quanh. Bản chất thường xuân là một dòng dây leo nên nó phù hợp nhất cho các vị trí cạnh cửa sổ hoặc ngoài ban công. Bạn có thể dễ dàng nhận ra dòng cây này bởi sự phổ biến của nó.'),
('PRO009', 'Cây tuyến tùng', 130, 150000, 'TYP003', 'SUP009', 'img/caytuyettung.jpg', 'Cây tuyết tùng xuất phát từ Nhật Bản, mang ý nghĩa vô cùng thiêng liêng. Người Nhật Bản cho rằng trong mỗi cây tuyết tùng đều ẩn chứa một vị thần phù hộ cho gia chủ. Về mặt thẩm mỹ, đây là một dòng cây bonsai cỡ nhỏ vô cùng xinh xắn, thích hợp làm cây trồng trong nhà.'),
('PRO010', 'Cây vạn niên thanh', 110, 150000, 'TYP003', 'SUP010', 'img/cayvannienthanh.jpg', 'Cây vạn niên thanh là loại cây cảnh rất dễ trồng trong nhà với nhiều kích thước lựa chọn. Đây là loại cây ưa bóng râm và cần ít ẩm; nên mỗi tuần bạn chỉ nên tưới nước từ 1 đến 2 lần. Lá cây có màu trắng ở gân lá, chuyển xanh dần ra phần viền lá rất đẹp mắt.'),
('PRO011', 'Cẩm tú cầu', 90, 150000, 'TYP004', 'SUP001', 'img/camtucau.png', NULL),
('PRO012', 'Cây kim ngân', 200, 150000, 'TYP003', 'SUP002', 'img/caykimngan.jpg', 'Cây kim ngân là loại cây thân dẻo được ưa chuộng làm cây trồng trong nhà. Cây thường được trồng thành cụm. Các thân cây đan vào nhau mà vươn lên như tết tóc khá lạ mắt. Lá cây xoè ra 5 nhánh, tượng trưng cho ngũ hành: Kim – Mộc – Thuỷ – Hoả – Thổ. Do vậy, kim ngân mang ý nghĩa mọi điều đều thuận lợi, tốt đẹp.'),
('PRO013', 'Cây kim tiền', 170, 150000, 'TYP003', 'SUP003', 'img/caykimtien.jpg', NULL),
('PRO014', 'Cây lưỡi hổ', 210, 150000, 'TYP001', 'SUP004', 'img/cayluoiho.jpg', 'Trái ngược với đa số cây xanh trên thế giới, cây lưỡi hổ hấp thụ khí CO2 và thải O2 vào ban đêm. Điều này giúp nó trở thành loại cây cực kỳ thích hợp cho phòng ngủ. Không ngạc nhiên khi đây là cây trồng trong nhà được dùng nhiều nhất hiện nay.'),
('PRO015', 'Cây phát tài', 220, 150000, 'TYP003', 'SUP005', 'img/cayphattai.jpg', NULL),
('PRO016', 'Cây sen đá', 140, 150000, 'TYP004', 'SUP006', 'img/caysenda.jpg', NULL),
('PRO017', 'Cây trầu bà', 130, 150000, 'TYP005', 'SUP007', 'img/caytrauba.jpg', 'Trầu bà rất dễ trồng và không cần chăm sóc nhiều. Trong điều kiện thiếu nước và dưỡng chất, cây vẫn có thể phát triển tốt. Vốn là loài cây cây leo, trầu bà thích hợp trồng ở bên cửa sổ, trên kệ tủ hoặc kết hợp bể thuỷ sinh cũng rất đẹp mắt.');

--
-- Triggers `product`
--
DELIMITER $$
CREATE TRIGGER `before_insert_product` BEFORE INSERT ON `product` FOR EACH ROW BEGIN
    DECLARE new_id INT;
    SELECT COALESCE(MAX(CAST(SUBSTRING(masp,4) AS UNSIGNED)),0) + 1 INTO new_id FROM product;
    SET NEW.masp = CONCAT('PRO', LPAD(CAST(new_id AS CHAR),3,'0')); 
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `producttype`
--

CREATE TABLE `producttype` (
  `maloaisp` varchar(20) NOT NULL,
  `tenloaisp` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `producttype`
--

INSERT INTO `producttype` (`maloaisp`, `tenloaisp`) VALUES
('TYP001', 'Cây dễ chăm'),
('TYP002', 'Cây văn phòng'),
('TYP003', 'Cây phong thủy'),
('TYP004', 'Cây để bàn'),
('TYP005', 'Cây trồng nước'),
('TYP006', 'Cây cao cấp'),
('TYP007', 'Chậu đất nung'),
('TYP008', 'Chậu xi măng');

--
-- Triggers `producttype`
--
DELIMITER $$
CREATE TRIGGER `before_insert_producttype` BEFORE INSERT ON `producttype` FOR EACH ROW BEGIN
    DECLARE new_id INT;
    SELECT COALESCE(MAX(CAST(SUBSTRING(maloaisp,4) AS UNSIGNED)),0) + 1 INTO new_id FROM producttype;
    SET NEW.maloaisp = CONCAT('TYP', LPAD(CAST(new_id AS CHAR),3,'0')); 
END
$$
DELIMITER ;

-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `mastaff` varchar(20) NOT NULL,
  `staffname` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `address` varchar(100) DEFAULT NULL,
  `powergroupid` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `mancc` varchar(20) NOT NULL,
  `tencc` varchar(25) NOT NULL,
  `diachi` varchar(100) NOT NULL,
  `dienthoai` varchar(11) NOT NULL,
  `sofax` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`mancc`, `tencc`, `diachi`, `dienthoai`, `sofax`) VALUES
('SUP001', 'Công ty Cây Xanh An Nhiên', '123 Nguyễn Văn Cừ, Q.5, TP.HCM', '0987654321', '02838450001'),
('SUP002', 'Vườn Cây Nam Phương', '45 Đinh Bộ Lĩnh, Q.Bình Thạnh, TP.HCM', '0912345678', '02835551234'),
('SUP003', 'Green House Garden', '678 Trần Hưng Đạo, Q.1, TP.HCM', '0977333111', '02836221212'),
('SUP004', 'Nhà Vườn Xuân Phát', '12/7 Lê Văn Việt, Q.9, TP.Thủ Đức', '0909090909', '02836664444'),
('SUP005', 'Hoa Kiểng Bảo Trân', '234 Lạc Long Quân, Q.11, TP.HCM', '0922222222', '02837778888'),
('SUP006', 'Cây Cảnh Minh Đức', '89 Tô Ký, Hóc Môn, TP.HCM', '0933333333', '02834567789'),
('SUP007', 'Sài Gòn Garden', '16 Phan Đăng Lưu, Q.Phú Nhuận, TP.HCM', '0944444444', '02839878888'),
('SUP008', 'Cây Xanh Phong Thủy Tâm A', '5 Nguyễn Oanh, Q.Gò Vấp, TP.HCM', '0955555555', '02836778899'),
('SUP009', 'Vườn Kiểng Đông Thảo', '111 Lê Lợi, Q.3, TP.HCM', '0966666666', '02832323232'),
('SUP010', 'Cây Cảnh Miền Nam', '71 Trường Chinh, Tân Bình, TP.HCM', '0977777777', '02838889999');

--
-- Triggers `supplier`
--
DELIMITER $$
CREATE TRIGGER `before_insert_supplier` BEFORE INSERT ON `supplier` FOR EACH ROW BEGIN
    DECLARE new_id INT;
    SELECT COALESCE(MAX(CAST(SUBSTRING(mancc,4) AS UNSIGNED)),0) + 1 INTO new_id FROM supplier;
    SET NEW.mancc = CONCAT('SUP', LPAD(CAST(new_id AS CHAR),3,'0')); 
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`mabill`),
  ADD KEY `fk_bill_macustomer` (`macustomer`),
  ADD KEY `fk_bill_maorder` (`maorder`),
  ADD KEY `fk_bill_mapayby` (`mapayby`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`magiohang`),
  ADD KEY `fk_cart_mauser` (`mauser`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`macomment`),
  ADD KEY `fk_comment_masp` (`masp`),
  ADD KEY `fk_comment_mauser` (`mauser`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`macustomer`);

--
-- Indexes for table `detail_entry_form`
--
ALTER TABLE `detail_entry_form`
  ADD PRIMARY KEY (`maphieunhap`,`masp`),
  ADD KEY `fk_detail_entry_form_masp` (`masp`);

--
-- Indexes for table `entry_form`
--
ALTER TABLE `entry_form`
  ADD PRIMARY KEY (`maphieunhap`),
  ADD KEY `fk_entry_form_mancc` (`mancc`),
  ADD KEY `fk_entry_form_mastaff` (`mastaff`);

--
-- Indexes for table `func`
--
ALTER TABLE `func`
  ADD PRIMARY KEY (`funcid`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`maorder`),
  ADD KEY `fk_order_magiohang` (`magiohang`),
  ADD KEY `fk_order_mauser` (`mauser`),
  ADD KEY `fk_order_mabill` (`mabill`);

--
-- Indexes for table `payby`
--
ALTER TABLE `payby`
  ADD PRIMARY KEY (`mapayby`);

--
-- Indexes for table `powergroup`
--
ALTER TABLE `powergroup`
  ADD PRIMARY KEY (`powergroupid`);

--
-- Indexes for table `powergroup_func`
--
ALTER TABLE `powergroup_func`
  ADD PRIMARY KEY (`powergroupid`,`funcid`),
  ADD KEY `fk_powergroup_func_func` (`funcid`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`masp`),
  ADD KEY `fk_product_maloaisp` (`maloaisp`),
  ADD KEY `fk_product_mancc` (`mancc`);

--
-- Indexes for table `producttype`
--
ALTER TABLE `producttype`
  ADD PRIMARY KEY (`maloaisp`);

--
-- Indexes for table `product_cart`
--
ALTER TABLE `product_cart`
  ADD PRIMARY KEY (`masp`,`magiohang`),
  ADD KEY `fk_product_cart_magiohang` (`magiohang`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`mastaff`),
  ADD KEY `fk_staff_powergroup` (`powergroupid`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`mancc`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `macomment` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `fk_bill_macustomer` FOREIGN KEY (`macustomer`) REFERENCES `customer` (`macustomer`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bill_maorder` FOREIGN KEY (`maorder`) REFERENCES `order` (`maorder`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bill_mapayby` FOREIGN KEY (`mapayby`) REFERENCES `payby` (`mapayby`) ON DELETE CASCADE;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_mauser` FOREIGN KEY (`mauser`) REFERENCES `customer` (`macustomer`) ON DELETE CASCADE;

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_masp` FOREIGN KEY (`masp`) REFERENCES `product` (`masp`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_comment_mauser` FOREIGN KEY (`mauser`) REFERENCES `customer` (`macustomer`) ON DELETE CASCADE;

--
-- Constraints for table `detail_entry_form`
--
ALTER TABLE `detail_entry_form`
  ADD CONSTRAINT `fk_detail_entry_form_maphieunhap` FOREIGN KEY (`maphieunhap`) REFERENCES `entry_form` (`maphieunhap`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_detail_entry_form_masp` FOREIGN KEY (`masp`) REFERENCES `product` (`masp`) ON DELETE CASCADE;

--
-- Constraints for table `entry_form`
--
ALTER TABLE `entry_form`
  ADD CONSTRAINT `fk_entry_form_mancc` FOREIGN KEY (`mancc`) REFERENCES `supplier` (`mancc`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_entry_form_mastaff` FOREIGN KEY (`mastaff`) REFERENCES `staff` (`mastaff`) ON DELETE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `fk_order_mabill` FOREIGN KEY (`mabill`) REFERENCES `bill` (`mabill`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_order_magiohang` FOREIGN KEY (`magiohang`) REFERENCES `cart` (`magiohang`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_mauser` FOREIGN KEY (`mauser`) REFERENCES `customer` (`macustomer`) ON DELETE CASCADE;

--
-- Constraints for table `powergroup_func`
--
ALTER TABLE `powergroup_func`
  ADD CONSTRAINT `fk_powergroup_func_func` FOREIGN KEY (`funcid`) REFERENCES `func` (`funcid`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_powergroup_func_powergroup` FOREIGN KEY (`powergroupid`) REFERENCES `powergroup` (`powergroupid`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `fk_product_maloaisp` FOREIGN KEY (`maloaisp`) REFERENCES `producttype` (`maloaisp`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_product_mancc` FOREIGN KEY (`mancc`) REFERENCES `supplier` (`mancc`) ON DELETE CASCADE;

--
-- Constraints for table `product_cart`
--
ALTER TABLE `product_cart`
  ADD CONSTRAINT `fk_product_cart_magiohang` FOREIGN KEY (`magiohang`) REFERENCES `cart` (`magiohang`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_product_cart_masp` FOREIGN KEY (`masp`) REFERENCES `product` (`masp`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_staff_powergroup` FOREIGN KEY (`powergroupid`) REFERENCES `powergroup` (`powergroupid`) ON DELETE SET NULL;
COMMIT;

ALTER TABLE bill CHANGE ngaymua bill_date DATETIME(6) DEFAULT CURRENT_TIMESTAMP(6);
ALTER TABLE bill
ADD COLUMN receiver_name VARCHAR(255) DEFAULT NULL;
ADD COLUMN phone_number VARCHAR(20) DEFAULT NULL;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
