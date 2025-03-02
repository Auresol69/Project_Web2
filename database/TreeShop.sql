create DATABASE TreeShop;

USE TreeShop;

create table IF NOT EXISTS Product (
    ProductID INT PRIMARY KEY AUTO_INCREMENT,
    ProductName VARCHAR(100) NOT NULL,
    ProductImage VARCHAR(255) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL
);

-- insert Hoa
INSERT INTO Product (ProductName,ProductImage,Price) VALUES('Cẩm Tú Cầu', 'img/meme.jpg',9999.99),
        ('Xuyến Chi', 'img/meme.jpg', 1599.99),
        ('Hồng', 'img/meme.jpg', 499.99),
        ('Trà Xanh', 'img/meme.jpg', 599.99),
        ('Châu Phi', 'img/meme.jpg', 299.99),
        ('Nâu', 'img/meme.jpg', 399.99),
        ('Đào', 'img/meme.jpg', 499.99),
        ('Cam', 'img/meme.jpg', 399.99),
        ('Chanh', 'img/meme.jpg', 499.99);

