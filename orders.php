<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Xử lý xóa đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_order_id'])) {
    $order_id = $_POST['delete_order_id'];
    $conn->prepare("DELETE FROM Order_Fabric_Rolls WHERE order_id = :order_id")->execute(['order_id' => $order_id]);
    $conn->prepare("DELETE FROM Order_Payments WHERE order_id = :order_id")->execute(['order_id' => $order_id]);
    $stmt = $conn->prepare("DELETE FROM Orders WHERE order_id = :order_id");
    $stmt->execute(['order_id' => $order_id]);
}

// Xử lý thanh toán đơn hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_order_id'])) {
    $order_id = $_POST['pay_order_id'];
    $payment_amount = floatval($_POST['payment_amount']);
    
    // Lấy tổng tiền đơn hàng và số tiền đã thanh toán
    $order_stmt = $conn->prepare("SELECT total_amount FROM Orders WHERE order_id = :order_id");
    $order_stmt->execute(['order_id' => $order_id]);
    $total_amount = $order_stmt->fetchColumn();

    $paid_stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) FROM Order_Payments WHERE order_id = :order_id");
    $paid_stmt->execute(['order_id' => $order_id]);
    $total_paid = $paid_stmt->fetchColumn();

    $remaining_balance = $total_amount - $total_paid;

    if ($payment_amount > $remaining_balance) {
        echo "<script>alert('Số tiền thanh toán vượt quá số dư. Vui lòng thử lại.');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO Order_Payments (order_id, payment_date, amount) VALUES (:order_id, NOW(), :amount)");
        $stmt->execute(['order_id' => $order_id, 'amount' => $payment_amount]);

        $new_status = ($payment_amount == $remaining_balance) ? 'paid' : 'partial_payment';
        $status_stmt = $conn->prepare("UPDATE Orders SET status = :status WHERE order_id = :order_id");
        $status_stmt->execute(['status' => $new_status, 'order_id' => $order_id]);
    }
}

// Xử lý tìm kiếm và sắp xếp
$query = isset($_GET['query']) ? $_GET['query'] : '';
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'order_id';
$order_dir = isset($_GET['order_dir']) && $_GET['order_dir'] === 'desc' ? 'desc' : 'asc';

$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

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

$total_stmt = $conn->prepare("SELECT COUNT(*) FROM Orders JOIN Customers ON Orders.customer_id = Customers.customer_id WHERE Customers.first_name LIKE :query OR Customers.last_name LIKE :query");
$total_stmt->execute([':query' => "%$query%"]);
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

$new_order_dir = $order_dir === 'asc' ? 'desc' : 'asc';
?>

<<!DOCTYPE html>
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

        .action-buttons form {
            display: inline-block;
        }

        .action-buttons .edit-btn, .action-buttons .delete-btn {
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            font-weight: bold;
            display: inline-block;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .action-buttons .edit-btn {
            background-color: #4CAF50;
        }

        .action-buttons .delete-btn {
            background-color: #f44336;
            border: none;
            cursor: pointer;
        }

        .action-buttons .edit-btn:hover, .action-buttons .delete-btn:hover {
            transform: scale(1.05);
            opacity: 0.9;
        }

        .action-buttons .delete-btn:active {
            transform: scale(0.95);
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
  <script>
        function openPaymentModal(orderId, remainingBalance) {
            const amount = prompt(`Nhập số tiền thanh toán (tối đa $${remainingBalance.toFixed(2)}):`);
            if (amount && parseFloat(amount) > 0 && parseFloat(amount) <= remainingBalance) {
                document.getElementById('pay_order_id').value = orderId;
                document.getElementById('payment_amount').value = parseFloat(amount);
                document.getElementById('payment_form').submit();
            } else {
                alert('Vui lòng nhập số tiền hợp lệ.');
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Danh Sách Đơn Hàng</h1>

        <form method="GET" action="orders.php" class="search-bar">
            <input type="text" name="query" placeholder="Tìm kiếm theo tên khách hàng..." value="<?= htmlspecialchars($query) ?>">
            <button type="submit">Tìm kiếm</button>
            <input type="hidden" name="order_by" value="<?= htmlspecialchars($order_by) ?>">
            <input type="hidden" name="order_dir" value="<?= htmlspecialchars($order_dir) ?>">
        </form>

        <form method="POST" id="payment_form">
            <input type="hidden" name="pay_order_id" id="pay_order_id">
            <input type="hidden" name="payment_amount" id="payment_amount">
        </form>

        <table>
            <tr>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=order_id&order_dir=<?= $new_order_dir ?>">ID</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=first_name&order_dir=<?= $new_order_dir ?>">Khách Hàng</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=emp_first_name&order_dir=<?= $new_order_dir ?>">Nhân Viên</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=order_date&order_dir=<?= $new_order_dir ?>">Ngày Đặt</a></th>
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=total_amount&order_dir=<?= $new_order_dir ?>">Tổng Tiền</a></th>
                <th>Số Tiền Chưa Thanh Toán</th> <!-- Thêm cột mới -->
                <th><a href="?query=<?= htmlspecialchars($query) ?>&order_by=status&order_dir=<?= $new_order_dir ?>">Trạng Thái</a></th>
                <th>Hành động</th>
            </tr>
            <?php if (!empty($orders)): ?>
                <?php foreach ($orders as $order): ?>
                    <?php
                    // Lấy số tiền đã thanh toán
                    $total_paid = $conn->query("SELECT COALESCE(SUM(amount), 0) FROM Order_Payments WHERE order_id = " . $order['order_id'])->fetchColumn();
                    $remaining_balance = $order['total_amount'] - $total_paid;
                    ?>
                    <tr>
                        <td><?= $order['order_id']; ?></td>
                        <td><?= htmlspecialchars($order['first_name'] . " " . $order['last_name']); ?></td>
                        <td><?= htmlspecialchars($order['emp_first_name'] . " " . $order['emp_last_name']); ?></td>
                        <td><?= htmlspecialchars($order['order_date']); ?></td>
                        <td><?= htmlspecialchars($order['total_amount']); ?></td>
                        <td><?= htmlspecialchars(number_format($total_paid, 2)); ?></td> <!-- Hiển thị số tiền đã thanh toán -->
                        <td><?= htmlspecialchars($order['status']); ?></td>
                        <td class="action-buttons">
                            <a href="edit_order.php?id=<?= $order['order_id']; ?>" class="edit-btn">Sửa</a>
                            <form method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?');" style="display:inline;">
                                <input type="hidden" name="delete_order_id" value="<?= $order['order_id']; ?>">
                                <button type="submit" class="delete-btn">Xóa</button>
                            </form>
                            <?php if ($order['status'] !== 'paid'): ?>
                                <button type="button" onclick="openPaymentModal(<?= $order['order_id']; ?>, <?= $remaining_balance; ?>)" class="pay-btn">Thanh toán</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" style="text-align: center;">Không tìm thấy đơn hàng nào.</td> <!-- Cập nhật colspan để khớp với cột mới -->
                </tr>
            <?php endif; ?>
        </table>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?query=<?= htmlspecialchars($query) ?>&order_by=<?= htmlspecialchars($order_by) ?>&order_dir=<?= htmlspecialchars($order_dir) ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>