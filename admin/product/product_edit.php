<?php
require 'connect_db.php'; 
include 'function.php';
$db = new Database(); // Tạo đối tượng Database
$pdo = $db->conn;

?>

<div class="main-content">
    <h1><?= !empty($_GET['id']) ? ((!empty($_GET['task']) && $_GET['task'] == "copy") ? "Copy sản phẩm" : "Sửa sản phẩm") : "Thêm sản phẩm" ?></h1>
    <div id="content-box">
        <?php
        if (isset($_GET['action']) && ($_GET['action'] == 'add' || $_GET['action'] == 'edit')) {
            if (isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['price']) && !empty($_POST['price'])) {
                $galleryImages = array();
                
                if (empty($_POST['name'])) {
                    $error = "Bạn phải nhập tên sản phẩm";
                } elseif (empty($_POST['price'])) {
                    $error = "Bạn phải nhập giá sản phẩm";
                } elseif (!empty($_POST['price']) && is_numeric(str_replace('.', '', $_POST['price'])) == false) {
                    $error = "Giá nhập không hợp lệ";
                }
                
                // Xử lý ảnh đại diện
                if (isset($_FILES['image']) && !empty($_FILES['image']['name'][0])) {
                    $uploadedFiles = $_FILES['image'];
                    $result = uploadFiles($uploadedFiles);
                    if (!empty($result['errors'])) {
                        $error = $result['errors'];
                    } else {
                        $image = $result['path'];
                    }
                }

                if (!isset($image) && !empty($_POST['image'])) {
                    $image = $_POST['image'];
                }

                // Xử lý thư viện ảnh
                if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
                    $uploadedFiles = $_FILES['gallery'];
                    $result = uploadFiles($uploadedFiles);
                    if (!empty($result['errors'])) {
                        $error = $result['errors'];
                    } else {
                        $galleryImages = $result['uploaded_files'];
                    }
                }

                if (!empty($_POST['gallery_image'])) {
                    $galleryImages = array_merge($galleryImages, $_POST['gallery_image']);
                }

                if (!isset($error)) {
                    try {
                        if ($_GET['action'] == 'edit' && !empty($_GET['id'])) {
                            // Cập nhật lại sản phẩm
                            $stmt = $pdo->prepare("UPDATE `product` SET `name` = :name, `quantity` = :quantity, `image` = :image, `price` = :price, `content` = :content, `last_updated` = :last_updated WHERE `id` = :id");
                            $stmt->execute([
                                ':name' => $_POST['name'],
                                ':quantity' => $_POST['quantity'],
                                ':image' => $image,
                                ':price' => str_replace('.', '', $_POST['price']),
                                ':content' => $_POST['content'],
                                ':last_updated' => time(),
                                ':id' => $_GET['id']
                            ]);
                        } else {
                            // Thêm sản phẩm
                            $stmt = $pdo->prepare("INSERT INTO `product` (`name`, `quantity`, `image`, `price`, `content`, `created_time`, `last_updated`) VALUES (:name, :quantity, :image, :price, :content, :created_time, :last_updated)");
                            $stmt->execute([
                                ':name' => $_POST['name'],
                                ':quantity' => $_POST['quantity'],
                                ':image' => $image,
                                ':price' => str_replace('.', '', $_POST['price']),
                                ':content' => $_POST['content'],
                                ':created_time' => time(),
                                ':last_updated' => time()
                            ]);
                        }

                        // Thêm vào thư viện ảnh
                        if (!empty($galleryImages)) {
                            $productId = ($_GET['action'] == 'edit' && !empty($_GET['id'])) ? $_GET['id'] : $pdo->lastInsertId();
                            $insertValues = [];
                            foreach ($galleryImages as $path) {
                                $insertValues[] = "(NULL, $productId, '$path', " . time() . ", " . time() . ")";
                            }
                            $valuesString = implode(',', $insertValues);
                            $pdo->exec("INSERT INTO `image_library` (`id`, `product_id`, `path`, `created_time`, `last_updated`) VALUES $valuesString;");
                        }

                        // Nếu thành công
                        echo '<div class="container"><div class="error">Cập nhật thành công!</div>';
                        echo '<a href="header.php?page=sanpham">Quay lại danh sách sản phẩm</a></div>';
                    } catch (PDOException $e) {
                        $error = "Có lỗi xảy ra: " . $e->getMessage();
                    }
                }
            } else {
                $error = "Bạn chưa nhập thông tin sản phẩm.";
            }

            // Hiển thị thông báo lỗi nếu có
            if (isset($error)) {
                echo '<div class="container"><div class="error">' . $error . '</div>';
                echo '<a href="header.php?page=sanpham">Quay lại danh sách sản phẩm</a></div>';
            }
        } else {
            // Hiển thị form chỉnh sửa nếu action không phải 'add' hoặc 'edit'
            if (!empty($_GET['id'])) {
                $stmt = $pdo->prepare("SELECT * FROM `product` WHERE `id` = :id");
                $stmt->execute([':id' => $_GET['id']]);
                $product = $stmt->fetch(PDO::FETCH_ASSOC);

                $stmt = $pdo->prepare("SELECT * FROM `image_library` WHERE `product_id` = :product_id");
                $stmt->execute([':product_id' => $_GET['id']]);
                $gallery = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($gallery)) {
                    foreach ($gallery as $row) {
                        $product['gallery'][] = array(
                            'id' => $row['id'],
                            'path' => $row['path'],
                        );
                    }
                }
            }
            ?>
            <form id="editing-form" method="POST" action="<?= (!empty($product) && !isset($_GET['task'])) ? "?action=edit&id=" . $_GET['id'] : "?action=add" ?>" enctype="multipart/form-data">
                <input type="submit" title="Lưu sản phẩm" value="Lưu" />
                <div class="clear-both"></div>
                <div class="wrap-field">
                    <label>Tên sản phẩm: </label>
                    <input type="text" name="name" value="<?= (!empty($product) ? htmlspecialchars($product['name']) : "") ?>" />
                    <div class="clear-both"></div>
                </div>
                <div class="wrap-field">
                    <label>Giá sản phẩm: </label>
                    <input type="text" name="price" value="<?= (!empty($product) ? number_format($product['price'], 0, ",", ".") : "") ?>" />
                    <div class="clear-both"></div>
                </div>
                <div class="wrap-field">
                    <label>Tồn kho: </label>
                    <input type="text" name="quantity" value="<?= (!empty($product) ? $product['quantity'] : "") ?>" />
                    <div class="clear-both"></div>
                </div>
                <div class="wrap-field">
                    <label>Ảnh đại diện: </label>
                    <div class="right-wrap-field">
                        <?php if (!empty($product['image'])) { ?>
                            <img src="../<?= htmlspecialchars($product['image']) ?>" /><br />
                            <input type="hidden" name="image" value="<?= htmlspecialchars($product['image']) ?>" />
                        <?php } ?>
                        <input type="file" name="image" />
                    </div>
                    <div class="clear-both"></div>
                </div>
                <div class="wrap-field">
                    <label>Thư viện ảnh: </label>
                    <div class="right-wrap-field">
                        <?php if (!empty($product['gallery'])) { ?>
                            <ul>
                                <?php foreach ($product['gallery'] as $image) { ?>
                                    <li>
                                        <img src="../<?= htmlspecialchars($image['path']) ?>" />
                                        <a href="gallery_delete.php?id=<?= $image['id'] ?>">Xóa</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                        <input multiple type="file" name="gallery[]" />
                    </div>
                    <div class="clear-both"></div>
                </div>
                <div class="wrap-field">
                    <label>Nội dung: </label>
                    <textarea name="content" id="product-content"><?= (!empty($product) ? htmlspecialchars($product['content']) : "") ?></textarea>
                    <div class="clear-both"></div>
                </div>
                </form>
            <div class="clear-both"></div>
            <script>
                // Thay thế <textarea id="product-content"> bằng CKEditor
                CKEDITOR.replace('product-content');
            </script>
            <?php 
        }
        ?>
    </div>
</div>