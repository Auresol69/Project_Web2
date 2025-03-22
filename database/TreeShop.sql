
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


create table IF NOT EXISTS cart (
    CartID INT(11) NOT NULL AUTO_INCREMENT,
    ProductID INT(11) NOT NULL,
    Quantity INT(11) DEFAULT 1,
    PRIMARY KEY (CartID),
    KEY (ProductID)
);

create table IF NOT EXISTS Orders (
    OrderID INT PRIMARY KEY AUTO_INCREMENT,
    CustomerName VARCHAR(100) NOT NULL,
    ProductID INT NOT NULL,
    Quantity INT DEFAULT 1,
    UnitPrice DECIMAL(10,2) NOT NULL,
    OrderDate DATETIME DEFAULT CURRENT_TIMESTAMP,
    Status VARCHAR(50) DEFAULT 'Giỏ hàng',
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);
