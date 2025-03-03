create DATABASE TreeShop;

USE TreeShop;

create table IF NOT EXISTS Product (
    ProductID INT PRIMARY KEY AUTO_INCREMENT,
    ProductName VARCHAR(100) NOT NULL,
    ProductType VARCHAR(100) NOT NULL,
    ProductImage VARCHAR(255) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL
);

-- insert Hoa
INSERT INTO Product (ProductName,ProductType,ProductImage,Price) VALUES('Cẩm Tú Cầu', 'Hoa','img/meme.jpg',9999.99),
        ('Xuyến Chi','Hoa' ,'img/meme.jpg', 1599.99),
        ('Hồng','Hoa', 'img/meme.jpg', 499.99),
        ('Trà Xanh','Cây giống','img/meme.jpg', 599.99),
        ('Nâu', 'Cây giống','img/meme.jpg', 399.99),
        ('Đào', 'Cây ăn trái' ,'img/meme.jpg', 499.99),
        ('Cam','Cây ăn trái' ,'img/meme.jpg', 399.99),
        ('Chanh','Cây ăn trái' ,'img/meme.jpg', 499.99);

