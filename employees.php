<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Thiết lập phân trang
$limit = 10; // Số bản ghi trên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Xử lý tìm kiếm và sắp xếp
$query = isset($_GET['query']) ? $_GET['query'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'employee_id';
$order_dir = isset($_GET['order_dir']) && $_GET['order_dir'] === 'desc' ? 'desc' : 'asc';

// Truy vấn SQL tìm kiếm và sắp xếp nhân viên
$sql = "SELECT * FROM Employees 
        WHERE first_name LIKE :query OR last_name LIKE :query OR phone LIKE :query 
        ORDER BY $order_by $order_dir 
        LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Tính tổng số bản ghi để tính tổng số trang
$total_stmt = $conn->prepare("SELECT COUNT(*) FROM Employees WHERE first_name LIKE :query OR last_name LIKE :query OR phone LIKE :query");
$total_stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
$total_stmt->execute();
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

// Đảo chiều sắp xếp
$new_order_dir = $order_dir === 'asc' ? 'desc' : 'asc';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Nhân viên</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            width: 100%;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        .search-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .search-bar input[type="text"] {
            padding: 8px;
            width: 100%;
            max-width: 400px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
        }
        .search-bar button {
            background-color: #333;
            color: white;
            border: none;
            padding: 8px 16px;
            margin-left: 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: 0.3s;
        }
        .search-bar button:hover {
            background-color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: white;
            cursor: pointer;
            position: relative;
        }
        th a.sortable {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        th a.sortable:hover {
            text-decoration: underline;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .action-buttons a {
            padding: 6px 12px;
            margin: 2px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            transition: 0.2s;
        }
        .edit-btn {
            background-color: #4CAF50;
        }
        .edit-btn:hover {
            background-color: #45a049;
        }
        .delete-btn {
            background-color: #f44336;
        }
        .delete-btn:hover {
            background-color: #d32f2f;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination a {
            color: #333;
            padding: 8px 12px;
            margin: 0 4px;
            border-radius: 5px;
            border: 1px solid #ddd;
            text-decoration: none;
            transition: 0.3s;
        }
        .pagination a:hover {
            background-color: #333;
            color: #fff;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
            border: 1px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Danh Sách Nhân Viên</h1>

        <!-- Form tìm kiếm nhân viên -->
        <form method="GET" action="employees.php" class="search-bar">
            <input type="text" name="query" placeholder="Tìm theo tên hoặc số điện thoại..." value="<?= htmlspecialchars($query) ?>">
            <button type="submit">Tìm kiếm</button>
            <input type="hidden" name="order_by" value="<?= htmlspecialchars($order_by) ?>">
            <input type="hidden" name="order_dir" value="<?= htmlspecialchars($order_dir) ?>">
        </form>

        <!-- Bảng hiển thị danh sách nhân viên -->
        <table>
            <tr>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=employee_id&order_dir=<?= $new_order_dir ?>" class="sortable">ID</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=first_name&order_dir=<?= $new_order_dir ?>" class="sortable">Tên</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=gender&order_dir=<?= $new_order_dir ?>" class="sortable">Giới tính</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=role&order_dir=<?= $new_order_dir ?>" class="sortable">Chức vụ</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=phone&order_dir=<?= $new_order_dir ?>" class="sortable">Số điện thoại</a></th>
                <th>Hành động</th>
            </tr>
            <?php if (!empty($employees)): ?>
                <?php foreach ($employees as $employee): ?>
                    <tr>
                        <td><?= htmlspecialchars($employee['employee_id']); ?></td>
                        <td><?= htmlspecialchars($employee['first_name'] . " " . $employee['last_name']); ?></td>
                        <td><?= htmlspecialchars($employee['gender']); ?></td>
                        <td><?= htmlspecialchars($employee['role']); ?></td>
                        <td><?= htmlspecialchars($employee['phone']); ?></td>
                        <td class="action-buttons">
                            <a href="edit_employee.php?id=<?= $employee['employee_id']; ?>" class="edit-btn">Sửa</a>
                            <a href="delete_employee.php?id=<?= $employee['employee_id']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Không tìm thấy nhân viên nào.</td>
                </tr>
            <?php endif; ?>
        </table>

        <!-- Phân trang -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&query=<?= htmlspecialchars($query) ?>&order_by=<?= htmlspecialchars($order_by) ?>&order_dir=<?= htmlspecialchars($order_dir) ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>
