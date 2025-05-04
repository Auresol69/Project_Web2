<?php
require_once __DIR__ . '/../connect_db.php';
$db = new connect_db();

// Get system statistics
$total_customers = $db->query("SELECT COUNT(*) FROM customer")->fetchColumn();
$monthly_sales = $db->query("SELECT SUM(tongtien) FROM bill WHERE MONTH(ngaymua) = MONTH(CURRENT_DATE())")->fetchColumn();
$top_products = $db->query("SELECT p.tensp as name, SUM(pc.soluong) as total_sold 
                           FROM product_cart pc 
                           JOIN product p ON pc.masp = p.masp 
                           GROUP BY p.masp 
                           ORDER BY total_sold DESC 
                           LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="./css/dashboard_styles.css">

<div class="main-contain">
    <h1 class="dashboard-title">System Dashboard</h1>
    
    <div class="stats-grid">
        <!-- Stat Card 1: Total Customers -->
        <div class="stat-card">
            <div class="stat-value"><?= number_format($total_customers) ?></div>
            <div class="stat-label">Total Customers</div>
            <div class="stat-icon">👥</div>
        </div>
        
        <!-- Stat Card 2: Monthly Sales -->
        <div class="stat-card">
            <div class="stat-value"><?= number_format($monthly_sales, 0, ',', '.') ?>đ</div>
            <div class="stat-label">Monthly Sales</div>
            <div class="stat-icon">💰</div>
        </div>
        
        <!-- Stat Card 3: Top Products -->
        <div class="stat-card wide-card">
            <h3>Top Products</h3>
            <ul class="top-products-list">
                <?php foreach($top_products as $product): ?>
                <li>
                    <span class="product-name"><?= $product['name'] ?></span>
                    <span class="product-sales"><?= $product['total_sold'] ?> sold</span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>
