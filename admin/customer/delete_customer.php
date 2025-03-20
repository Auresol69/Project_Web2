<!-- Xóa khách hàng -->
<?php
require_once 'connect_db.php';
$db = new connect_db();
$id = $_GET['id'];
$db->delete("users", $id);
header("Location: customer_listing.php");
exit();
?>