<?php
require_once '../connect_db.php';
$db = new connect_db();

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'pagination':
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 5;
        $id = $_GET['id'] ?? '';
        $name = $_GET['name'] ?? '';

        $params = [];
        $where = " WHERE 1=1 ";
        if ($id !== '') {
            $where .= " AND macustomer LIKE :id ";
            $params['id'] = "%$id%";
        }
        if ($name !== '') {
            $where .= " AND name LIKE :name ";
            $params['name'] = "%$name%";
        }

        $totalQuery = "SELECT COUNT(*) FROM customer $where";
        $totalStmt = $db->query($totalQuery, $params);
        $totalRecordsCount = $totalStmt->fetchColumn();

        $totalPages = ceil($totalRecordsCount / $per_page);
        $offset = ($page - 1) * $per_page;

        $query = "SELECT * FROM customer $where ORDER BY macustomer ASC LIMIT $offset, $per_page";
        $stmt = $db->query($query, $params);
        $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'customers' => $customers,
            'totalRecordsCount' => $totalRecordsCount,
            'totalPages' => $totalPages,
        ]);
        break;

    case 'delete':
        $id = $_GET['id'] ?? '';
        if ($id === '') {
            echo json_encode(['success' => false, 'message' => 'ID không hợp lệ']);
            exit;
        }
        $customer = $db->getById('customer', $id);
        if (!$customer) {
            echo json_encode(['success' => false, 'message' => 'Khách hàng không tồn tại']);
            exit;
        }
        $deleted = $db->delete('customer', $id);
        echo json_encode(['success' => $deleted]);
        break;

    case 'add':
        $data = json_decode(file_get_contents(filename: 'php://input'), true);
        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $powergroupid = trim($data['powergroupid'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $password = $data['password'] ?? '';

        if ($name === '' || $email === ''|| $powergroupid === '' || $phone === '' || $password === '') {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin']);
            exit;
        }

        $existingCustomer = $db->query("SELECT macustomer FROM customer WHERE email = ?", [$email])->fetch();
        if ($existingCustomer) {
            echo json_encode(['success' => false, 'message' => 'Email đã tồn tại']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $customerData = [
            'name' => $name,
            'email' => $email,
            'powergroupid' => $powergroupid,
            'phone' => $phone,
            'password' => $hashedPassword,
        ];

        $inserted = $db->insert('customer', $customerData);
        echo json_encode(['success' => (bool)$inserted]);
        break;

    case 'edit':
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            echo json_encode(['success' => false, 'message' => 'Dữ liệu không hợp lệ']);
            exit;
        }
        $id = $data['id'] ?? '';
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $powergroupid = trim($data['powergroupid'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $password = $data['password'] ?? '';

        if ($id === '' || $name === '' || $powergroupid === '' || $email === '' || $phone === '') {
            echo json_encode(['success' => false, 'message' => 'Vui lòng nhập đầy đủ thông tin']);
            exit;
        }

        $customer = $db->getById('customer', $id);
        if (!$customer) {
            echo json_encode(['success' => false, 'message' => 'Khách hàng không tồn tại']);
            exit;
        }

        $updateData = [
            'name' => $name,
            'email' => $email,
            'powergroupid' => $powergroupid,
            'phone' => $phone,
        ];

        if ($password !== '') {
            $updateData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $updated = $db->update('customer', $updateData, $id);
        echo json_encode(['success' => (bool)$updated]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
        break;
}