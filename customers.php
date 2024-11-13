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
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'customer_id';
$order_dir = isset($_GET['order_dir']) && $_GET['order_dir'] === 'desc' ? 'desc' : 'asc';

// Thiết lập phân trang
$limit = 10; // Số bản ghi mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Chuẩn bị câu truy vấn SQL cho tìm kiếm và sắp xếp có phân trang
$sql = "SELECT * FROM Customers WHERE first_name LIKE :query OR last_name LIKE :query OR phone LIKE :query ORDER BY $order_by $order_dir LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':query', "%$query%", PDO::PARAM_STR);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Đếm tổng số bản ghi để tính tổng số trang
$total_stmt = $conn->prepare("SELECT COUNT(*) FROM Customers WHERE first_name LIKE :query OR last_name LIKE :query OR phone LIKE :query");
$total_stmt->execute([':query' => "%$query%"]);
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

// Đảo chiều sắp xếp để sử dụng cho các lần nhấp tiếp theo
$new_order_dir = $order_dir === 'asc' ? 'desc' : 'asc';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Khách hàng</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Đặt kiểu nền và phông chữ */
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        /* Tạo container cho trang */
        .container {
            max-width: 900px;
            width: 100%;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Style cho tiêu đề */
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Style cho thanh tìm kiếm */
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

        /* Style cho bảng */
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

        /* Style cho các hàng trong bảng */
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Style cho các nút hành động */
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
        <h1>Danh Sách Khách Hàng</h1>

        <!-- Form tìm kiếm khách hàng -->
        <form method="GET" action="customers.php" class="search-bar">
            <input type="text" name="query" placeholder="Tìm theo tên hoặc số điện thoại..." value="<?= htmlspecialchars($query) ?>">
            <button type="submit">Tìm kiếm</button>
            <input type="hidden" name="order_by" value="<?= htmlspecialchars($order_by) ?>">
            <input type="hidden" name="order_dir" value="<?= htmlspecialchars($order_dir) ?>">
        </form>

        <!-- Bảng hiển thị danh sách khách hàng -->
        <table>
            <tr>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=customer_id&order_dir=<?= $new_order_dir ?>">ID</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=first_name&order_dir=<?= $new_order_dir ?>">Tên</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=phone&order_dir=<?= $new_order_dir ?>">Số điện thoại</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=outstanding_balance&order_dir=<?= $new_order_dir ?>">Công nợ</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=warning_status&order_dir=<?= $new_order_dir ?>">Trạng thái</a></th>
                <th>Hành động</th>
            </tr>
            <?php if (!empty($customers)): ?>
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= $customer['customer_id']; ?></td>
                        <td><?= htmlspecialchars($customer['first_name'] . " " . $customer['last_name']); ?></td>
                        <td><?= htmlspecialchars($customer['phone']); ?></td>
                        <td><?= htmlspecialchars($customer['outstanding_balance']); ?></td>
                        <td>
                            <?php 
                            if ($customer['bad_debt_status']) {
                                echo '<span style="color: red;">Nợ xấu</span>';
                            } elseif ($customer['warning_status']) {
                                echo '<span style="color: orange;">Cảnh báo</span>';
                            } else {
                                echo 'Bình thường';
                            }
                            ?>
                        </td>
                        <td class="action-buttons">
                            <a href="edit_customer.php?id=<?= $customer['customer_id']; ?>" class="edit-btn">Sửa</a>
                            <a href="delete_customer.php?id=<?= $customer['customer_id']; ?>" class="delete-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center;">Không tìm thấy khách hàng nào.</td>
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
