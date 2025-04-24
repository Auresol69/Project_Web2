<?php

function checkPrivilege($uri = false) {
    $uri = $uri != false ? $uri : $_SERVER['REQUEST_URI'];
    if(empty($_SESSION['current_user']['privileges'])){
        return false;
    }
    $privileges = $_SESSION['current_user']['privileges'];
    $privileges = implode("|", $privileges);
    preg_match('/dashboard\.php$|' . $privileges . '/', $uri, $matches);
    return !empty($matches);
}

function deleteChildrenMenu($parent_id, $menuList, $con) {
    foreach ($menuList as $item) {
        if ($item['parent_id'] == $parent_id) {
            deleteChildrenMenu($item['id'], $menuList, $con);
            mysqli_query($con, "DELETE FROM `menu` WHERE `id` = " . $item['id']);
        }
    }
}

function showMenuSelectBox($list, $num, $parent_id) {
    $num++;
    foreach ($list as $item) {
        $selected = "";
        if ($item['id'] == $parent_id) {
            $selected = "selected";
        }
        echo "<option value='" . $item['id'] . "' " . $selected . ">" . str_repeat("---", $num - 1) . $item['name'] . "</option>";
        if (!empty($item['children'])) {
            showMenuSelectBox($item['children'], $num, $parent_id);
        }
    }
}

function showMenuTree($list, $num, $config_name) {
    $num++;
    foreach ($list as $item) {
        echo renderTemplate('admin/li-template.php', array('num' => $num, 'config_name' => $config_name, 'row' => $item));
        if (!empty($item['children'])) {
            showMenuTree($item['children'], $num, $config_name);
        }
    }
}

function renderTemplate($filePath, $params) {
    $output = "";
    // Extract the variables to a local namespace
    extract($params);

    // Start output buffering
    ob_start();

    // Include the template file
    include $filePath;

    // End buffering and return its contents
    $output = ob_get_clean();
    return $output;
}

function createMenuTree(&$menuList, $parent_id) {
    $menuTree = array();
    foreach ($menuList as $key => $menu) {
        if ($menu['parent_id'] == $parent_id) {
            $children = createMenuTree($menuList, $menu['id']);
            if ($children) {
                $menu['children'] = $children;
            }
            $menuTree[] = $menu;
            unset($menuList[$key]);
        }
    }
    return $menuTree;
}

function getAllFiles() {
    $allFiles = array();
    $allDirs = glob('uploads/*');
    foreach ($allDirs as $dir) {
        $allFiles = array_merge($allFiles, glob($dir . "/*"));
    }
    return $allFiles;
}

function uploadFiles($uploadedFiles) {
    $files = array();
    $errors = array();
    $returnFiles = array();
    
    foreach ($uploadedFiles as $key => $values) {
        if (is_array($values)) {
            foreach ($values as $index => $value) {
                $files[$index][$key] = $value;
            }
        } else {
            $files[$key] = $values;
        }
    }
    $uploadPath = "../uploads/" . date('d-m-Y', time());
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }
    if (is_array(reset($files))) { // Nếu là nhiều ảnh
        foreach ($files as $file) {
            $result = processUploadFile($file, $uploadPath);
            if (!$result || isset($result['error']) && $result['error']) {
                $errors[] = isset($result['message']) ? $result['message'] : "Unknown error occurred";
            } else {
                $returnFiles[] = isset($result['path']) ? $result['path'] : null;
            }
        }
    } else { // Nếu là 1 ảnh
        $result = processUploadFile($files, $uploadPath);
        if (!$result || isset($result['error']) && $result['error']) {
            return array(
                'errors' => isset($result['message']) ? $result['message'] : "Unknown error occurred"
            );
        } else {
            return array(
                'path' => isset($result['path']) ? $result['path'] : null
            );
        }
    }
    return array(
        'errors' => $errors,
        'uploaded_files' => $returnFiles
    );
}

function processUploadFile($file, $uploadPath) {
    $file = validateUploadFile($file, $uploadPath);
    if ($file === false) {
        return array(
            'error' => true,
            'message' => "File tải lên không hợp lệ."
        );
    }
    
    $file["name"] = str_replace(' ', '_', $file["name"]);
    if (move_uploaded_file($file["tmp_name"], $uploadPath . '/' . $file["name"])) {
        return array(
            'error' => false,
            'path' => str_replace('../', '', $uploadPath) . '/' . $file["name"]
        );
    }
    
    return array(
        'error' => true,
        'message' => "Không thể di chuyển file tải lên."
    );
}

function validateUploadFile($file, $uploadPath) {
    // Kiểm tra xem có vượt quá dung lượng cho phép không?
    if ($file['size'] > 2 * 1024 * 1024) { // max upload is 2 Mb = 2 * 1024 kb * 1024 byte
        return false;
    }

    // Kiểm tra kiểu file có hợp lệ không?
    $validTypes = array("jpg", "jpeg", "png", "bmp", "xlsx", "xls");
    $fileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($fileType, $validTypes)) {
        return false;
    }

    // Kiểm tra xem file đã tồn tại chưa? Nếu tồn tại thì đổi tên
    $num = 0;
    $fileName = pathinfo($file['name'], PATHINFO_FILENAME);
    while (file_exists($uploadPath . '/' . $fileName . '.' . $fileType)) {
        $fileName = $fileName . " (" . $num . ")";
        $num++;
    }

    // Đảm bảo rằng tên file đã được thay đổi
    $file['name'] = $fileName . '.' . $fileType;
    return $file;
}

//Hàm login sau khi mạng xã hội trả dữ liệu về
function loginFromSocialCallBack($socialUser) {
    include './connect_db.php';
    $result = mysqli_query($con, "Select `id`,`username`,`email`,`fullname` from `user` WHERE `email` ='" . $socialUser['email'] . "'");
    if ($result->num_rows == 0) {
        $result = mysqli_query($con, "INSERT INTO `user` (`fullname`,`email`, `status`, `created_time`, `last_updated`) VALUES ('" . $socialUser['name'] . "', '" . $socialUser['email'] . "', 1, " . time() . ", '" . time() . "');");
        if (!$result) {
            echo mysqli_error($con);
            exit;
        }
        $result = mysqli_query($con, "Select `id`,`username`,`email`,`fullname` from `user` WHERE `email` ='" . $socialUser['email'] . "'");
    }
    if ($result->num_rows > 0) {
        $user = mysqli_fetch_assoc($result);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['current_user'] = $user;
        header('Location: ./login.php');
    }
}

function validateDateTime($date) {
    //Kiểm tra định dạng ngày tháng xem đúng DD/MM/YYYY hay chưa?
    preg_match('/^[0-9]{1,2}-[0-9]{1,2}-[0-9]{4}$/', $date, $matches);
    if (count($matches) == 0) { //Nếu ngày tháng nhập không đúng định dạng thì $match = array(); (rỗng)
        return false;
    }
    $separateDate = explode('-', $date);
    $day = (int) $separateDate[0];
    $month = (int) $separateDate[1];
    $year = (int) $separateDate[2];
    //Nếu là tháng 2
    if ($month == 2) {
        if ($year % 4 == 0) { //Nếu là năm nhuận
            if ($day > 29) {
                return false;
            }
        } else { //Không phải năm nhuận
            if ($day > 28) {
                return false;
            }
        }
    }
    //Check các tháng khác
    switch ($month) {
        case 1:
        case 3:
        case 5:
        case 7:
        case 8:
        case 10:
        case 12:
            if ($day > 31) {
                return false;
            }
            break;
        case 4:
        case 6:
        case 9:
        case 11:
            if ($day > 30) {
                return false;
            }
            break;
    }
    return true;
}

?>
