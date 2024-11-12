<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php'; // Kết nối cơ sở dữ liệu
include 'nav.php'; // Bao gồm thanh điều hướng

// Xử lý tìm kiếm và sắp xếp
$query = isset($_GET['query']) ? $_GET['query'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'order_id';
$order_dir = isset($_GET['order_dir']) && $_GET['order_dir'] === 'desc' ? 'desc' : 'asc';

// Thiết lập phân trang
$limit = 10; // Số bản ghi trên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Truy vấn SQL tìm kiếm và sắp xếp có phân trang
$sql = "SELECT Orders.*, 
               Customers.first_name, Customers.last_name, 
               Employees.first_name AS emp_first_name, Employees.last_name AS emp_last_name 
        FROM Orders
        JOIN Customers ON Orders.customer_id = Customers.customer_id
        JOIN Employees ON Orders.employee_id = Employees.employee_id
        WHERE Customers.first_name LIKE :query OR Customers.last_name LIKE :query 
        ORDER BY $order_by $order_dir
        LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Đếm tổng số bản ghi để tính tổng số trang
$total_stmt = $conn->prepare("SELECT COUNT(*) FROM Orders JOIN Customers ON Orders.customer_id = Customers.customer_id WHERE Customers.first_name LIKE :query OR Customers.last_name LIKE :query");
$total_stmt->execute([':query' => "%$query%"]);
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

// Đảo chiều sắp xếp
$new_order_dir = $order_dir === 'asc' ? 'desc' : 'asc';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Đơn Hàng</title>
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

        .delete-btn {
            background-color: #f44336;
        }

        /* Style cho phân trang */
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
        <h1>Danh Sách Đơn Hàng</h1>

        <!-- Form tìm kiếm đơn hàng -->
        <form method="GET" action="orders.php" class="search-bar">
            <input type="text" name="query" placeholder="Tìm kiếm theo tên khách hàng..." value="<?= htmlspecialchars($query) ?>">
            <button type="submit">Tìm kiếm</button>
            <input type="hidden" name="order_by" value="<?= htmlspecialchars($order_by) ?>">
            <input type="hidden" name="order_dir" value="<?= htmlspecialchars($order_dir) ?>">
        </form>

        <!-- Bảng hiển thị danh sách đơn hàng -->
        <table>
            <tr>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=order_id&order_dir=<?= $new_order_dir ?>">ID</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=first_name&order_dir=<?= $new_order_dir ?>">Khách Hàng</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=emp_first_name&order_dir=<?= $new_order_dir ?>">Nhân Viên</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=order_date&order_dir=<?= $new_order_dir ?>">Ngày Đặt</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=total_amount&order_dir=<?= $new_order_dir ?>">Tổng Tiền</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=status&order_dir=<?= $new_order_dir ?>">Trạng Thái</a></th>
                <th>Hành động</th>
            </tr>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= $order['order_id']; ?></td>
                        <td><?= htmlspecialchars($order['first_name'] . " " . $order['last_name']); ?></td>
                        <td><?= htmlspecialchars($order['emp_first_name'] . " " . $order['emp_last_name']); ?></td>
                        <td><?= htmlspecialchars($order['order_date']); ?></td>
                        <td><?= htmlspecialchars($order['total_amount']); ?></td>
                        <td><?= htmlspecialchars($order['status']); ?></td>
                        <td class="action-buttons">
                            <a href="edit_order.php?id=<?= $order['order_id']; ?>" class="edit-btn">Sửa</a>
                            <a href="delete_order.php?id=<?= $order['order_id']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Không tìm thấy đơn hàng nào.</td>
                </tr>
            <?php endif; ?>
        </table>

        <!-- Hiển thị phân trang -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?query=<?= htmlspecialchars($query) ?>&order_by=<?= htmlspecialchars($order_by) ?>&order_dir=<?= htmlspecialchars($order_dir) ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>
