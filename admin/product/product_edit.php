<?php
include '../connect_db.php';
include 'function.php';

$db = new connect_db(); // Tạo đối tượng connect_db
?>

<div class="main-content">
    <h1><?= !empty($_GET['id']) ? (!empty($_GET['task']) && $_GET['task'] == "copy" ? "Copy sản phẩm" : "Sửa sản phẩm") : "Thêm sản phẩm" ?></h1>
    <div id="content-box">
        <?php
        if (isset($_GET['action']) && in_array($_GET['action'], ['add', 'edit'])) {
            $error = null;
            $galleryImages = [];

            // Kiểm tra thông tin đầu vào
            if (isset($_POST['name'], $_POST['price']) && !empty($_POST['name']) && !empty($_POST['price'])) {
                // Kiểm tra giá trị đầu vào
                if (empty($_POST['name'])) {
                    $error = "Bạn phải nhập tên sản phẩm";
                } elseif (empty($_POST['price'])) {
                    $error = "Bạn phải nhập giá sản phẩm";
                } elseif (!is_numeric(str_replace('.', '', $_POST['price']))) {
                    $error = "Giá nhập không hợp lệ";
                }

                // Xử lý ảnh đại diện
                if (isset($_FILES['image']) && !empty($_FILES['image']['name'])) {
                    $uploadedFiles = $_FILES['image'];
                    $result = uploadFiles($uploadedFiles);
                    if ($result === false || !isset($result['path'])) {
                        $error = "Không thể tải lên ảnh đại diện.";
                    } else {
                        $image = $result['path'];
                    }
                }

                if (empty($error) && isset($_POST['image']) && !empty($_POST['image'])) {
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

                if (isset($_POST['gallery_image']) && !empty($_POST['gallery_image'])) {
                    $galleryImages = array_merge($galleryImages, $_POST['gallery_image']);
                }

                if (empty($error)) {
                    try {
                        if ($_GET['action'] == 'edit' && !empty($_GET['id'])) {
                            // Cập nhật lại sản phẩm
                            $db->update('product', [
                                'name' => $_POST['name'],
                                'quantity' => $_POST['quantity'],
                                'image' => isset($image) ? $image : null,
                                'price' => str_replace('.', '', $_POST['price']),
                                'content' => $_POST['content'],
                                'last_updated' => time()
                            ], $_GET['id']);
                        } else {
                            // Thêm sản phẩm
                            $db->insert('product', [
                                'name' => $_POST['name'],
                                'quantity' => $_POST['quantity'],
                                'image' => isset($image) ? $image : null,
                                'price' => str_replace('.', '', $_POST['price']),
                                'content' => $_POST['content'],
                                'created_time' => time(),
                                'last_updated' => time()
                            ]);
                        }

                        // Thêm vào thư viện ảnh
                        if (!empty($galleryImages)) {
                            $productId = ($_GET['action'] == 'edit' && !empty($_GET['id'])) ? $_GET['id'] : $db->getLastInsertId();
                            foreach ($galleryImages as $path) {
                                $db->insert('image_library', [
                                    'product_id' => $productId,
                                    'path' => $path,
                                    'created_time' => time(),
                                    'last_updated' => time()
                                ]);
                            }
                        }

                        // Nếu thành công
                        echo '<div class="container"><div class="success">Cập nhật thành công!</div>';
                        echo '<a href="../header.php?page=sanpham">Quay lại danh sách sản phẩm</a></div>';
                    } catch (PDOException $e) {
                        $error = "Có lỗi xảy ra: " . $e->getMessage();
                        echo '<div class="container"><div class="error">' . $error . '</div>';
                        echo '<a href="../header.php?page=sanpham">Quay lại danh sách sản phẩm</a></div>';
                        exit;
                    }
                }
            } else {
                $error = "Bạn chưa nhập thông tin sản phẩm.";
            }

            // Hiển thị thông báo lỗi nếu có
            if (!empty($error)) {
                // Kiểm tra nếu $error là một mảng
                if (is_array($error)) {
                    // Chuyển đổi mảng thành chuỗi
                    $errorMessage = implode(", ", $error); // Hoặc bạn có thể sử dụng nl2br để phân cách bằng dòng mới
                } else {
                    // Nếu không phải là mảng, giữ nguyên
                    $errorMessage = $error;
                }
            
                echo '<div class="container"><div class="error">' . htmlspecialchars($errorMessage) . '</div>';
                echo '<a href="header.php?page=sanpham">Quay lại danh sách sản phẩm</a></div>';
            }
        } else {
            // Hiển thị form chỉnh sửa nếu action không phải 'add' hoặc 'edit'
            if (!empty($_GET['id'])) {
                $product = $db->getById('product', $_GET['id']);
                if ($product === false) {
                    $error = "Không tìm thấy sản phẩm.";
                } else {
                    // Lấy danh sách ảnh trong thư viện
                    $gallery = $db->query("SELECT * FROM `image_library` WHERE `product_id` = :product_id", ['product_id' => $_GET['id']])->fetchAll(PDO::FETCH_ASSOC);
                    if (!empty($gallery)) {
                        foreach ($gallery as $row) {
                            $product['gallery'][] = [
                                'id' => $row['id'],
                                'path' => $row['path'],
                            ];
                        }
                    }
                }
            }
            ?>
            <form id="editing-form" method="POST" action="<?= !empty($product) ? "?action=edit&id=" . $_GET['id'] : "?action=add" ?>" enctype="multipart/form-data">
                <input type="submit" title="Lưu sản phẩm" value="Lưu" />
                <div class="clear-both"></div>
                <div class="wrap-field">
                    <label>Tên sản phẩm: </label>
                    <input type="text" name="name" value="<?= !empty($product) ? htmlspecialchars($product['name']) : "" ?>" required />
                    <div class="clear-both"></div>
                </div>
                <div class="wrap-field">
                    <label>Giá sản phẩm: </label>
                    <input type="text" name="price" value="<?= !empty($product) ? number_format($product['price'], 0, ",", ".") : "" ?>" required />
                    <div class="clear-both"></div>
                </div>
                <div class="wrap-field">
                    <label>Tồn kho: </label>
                    <input type="text" name="quantity" value="<?= !empty($product) ? $product['quantity'] : "" ?>" required />
                    <div class="clear-both"></div>
                </div>
                <div class="wrap-field">
                    <label>Ảnh đại diện: </label>
                    <div class="right-wrap-field">
                        <?php if (!empty($product['image'])) { ?>
                            <img src="../<?= htmlspecialchars($product['image']) ?>" class="preview-image"/><br />
                            <input type="hidden" name="image" value="<?= htmlspecialchars($product['image']) ?>" />
                        <?php } ?>
                        <input type="file" name="image" accept="image/*" />
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
                                        <img src="../<?= htmlspecialchars($image['path']) ?>" class="preview-image" />
                                        <a href="gallery_delete.php?id=<?= $image['id'] ?>">Xóa</a>
                                    </li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                        <input type="file" multiple name="gallery[]" accept="image/*" />
                    </div>
                    <div class="clear-both"></div>
                </div>
                <div class="wrap-field">
                    <label>Nội dung: </label>
                    <textarea name="content" id="product-content"><?= !empty($product) ? htmlspecialchars($product['content']) : "" ?></textarea>
                    <div class="clear-both"></div>
                </div>
            </form>
            <div class="clear-both"></div>
            <script>
                // Thay thế <textarea id="product-content"> bằng CKEditor
                if (typeof CKEDITOR !== 'undefined') {
                    CKEDITOR.replace('product-content');
                }
            </script>
            <?php
        }
        ?>
    </div>
</div>