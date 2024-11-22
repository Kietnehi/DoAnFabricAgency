<?php
ob_start();
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Include the navigation bar

// Thiết lập phân trang
$limit = 10; // Số bản ghi trên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Nhận tham số order_by và order_dir từ URL
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'PaymentTime';
$order_dir = isset($_GET['order_dir']) && $_GET['order_dir'] === 'desc' ? 'desc' : 'asc';

// Xác định các cột hợp lệ để tránh SQL Injection
$valid_columns = ['CusId', 'Fname', 'Lname', 'PaymentTime', 'Amount'];
if (!in_array($order_by, $valid_columns)) {
    $order_by = 'PaymentTime';
}

// Xử lý form thêm thanh toán mới
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $order_code = $_POST['order_code'];
    $amount = $_POST['amount'];
    $payment_date = date('Y-m-d H:i:s');

    // Thêm thanh toán vào bảng `customer_partialpayments`
    $sql = "INSERT INTO customer_partialpayments (CusId, Amount, PaymentTime, OCode) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$customer_id, $amount, $payment_date, $order_code]);

    // Cập nhật công nợ của khách hàng
    $update_sql = "UPDATE customer SET Dept = Dept - ? WHERE CusId = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->execute([$amount, $customer_id]);

    // Cập nhật trạng thái đơn hàng
    $order_update_stmt = $conn->prepare("
        UPDATE orders 
        SET Status = CASE 
            WHEN (SELECT COALESCE(SUM(Amount), 0) FROM customer_partialpayments WHERE OCode = ?) >= TotalPrice 
            THEN 'completed' 
            ELSE 'partial_payment' 
        END
        WHERE OCode = ?
    ");
    $order_update_stmt->execute([$order_code, $order_code]);

    header("Location: customer_payments.php");
    exit();
}

// Truy vấn SQL với sắp xếp
$stmt = $conn->prepare("
    SELECT cp.CusId, cp.Amount, cp.PaymentTime, cp.OCode, c.Fname, c.Lname 
    FROM customer_partialpayments cp
    JOIN customer c ON cp.CusId = c.CusId 
    ORDER BY $order_by $order_dir
    LIMIT :limit OFFSET :offset
");
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy tổng số bản ghi để tính tổng số trang
$total_stmt = $conn->query("SELECT COUNT(*) FROM customer_partialpayments");
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

// Lấy danh sách khách hàng và đơn hàng
$customers = $conn->query("SELECT CusId, Fname, Lname, Dept FROM customer")->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Thanh Toán Khách Hàng</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="customer_payments.css">
</head>
<body>
    <div class="container">
        <h1>Danh Sách Thanh Toán Khách Hàng</h1>

        <table>
            <tr>
                <th>
                    <a href="?order_by=CusId&order_dir=<?= ($order_by === 'CusId' && $order_dir === 'asc') ? 'desc' : 'asc' ?>">
                        Mã Khách Hàng
                    </a>
                </th>
                <th>
                    <a href="?order_by=Fname&order_dir=<?= ($order_by === 'Fname' && $order_dir === 'asc') ? 'desc' : 'asc' ?>">
                        Khách Hàng
                    </a>
                </th>
                <th>
                    <a href="?order_by=PaymentTime&order_dir=<?= ($order_by === 'PaymentTime' && $order_dir === 'asc') ? 'desc' : 'asc' ?>">
                        Ngày Thanh Toán
                    </a>
                </th>
                <th>
                    <a href="?order_by=Amount&order_dir=<?= ($order_by === 'Amount' && $order_dir === 'asc') ? 'desc' : 'asc' ?>">
                        Số Tiền (USD)
                    </a>
                </th>
                <th>Thao Tác</th>
            </tr>
            <?php if (!empty($payments)): ?>
                <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td><?= htmlspecialchars($payment['CusId']); ?></td>
                        <td><?= htmlspecialchars($payment['Fname'] . " " . $payment['Lname']); ?></td>
                        <td><?= htmlspecialchars($payment['PaymentTime']); ?></td>
                        <td>$<?= htmlspecialchars(number_format($payment['Amount'], 2)); ?> USD</td>
                        <td>
                            <a href="customer_orders.php?customer_id=<?= htmlspecialchars($payment['CusId']); ?>">Xem Chi Tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Không có thanh toán nào.</td>
                </tr>
            <?php endif; ?>
        </table>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>

        <h2>Thêm Thanh Toán Mới</h2>
<form action="customer_payments.php" method="POST">
    <label for="customer_id">Khách Hàng:</label>
    <select name="customer_id" required>
        <?php foreach ($customers as $customer): ?>
            <option value="<?= htmlspecialchars($customer['CusId']) ?>">
                <?= htmlspecialchars($customer['Fname'] . " " . $customer['Lname']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="order_code">Mã Đơn Hàng:</label>
    <select name="order_code" required>
        <?php
        // Lấy danh sách đơn hàng của khách hàng
        if (!empty($customers)) {
            $orders_stmt = $conn->prepare("SELECT OCode, TotalPrice FROM orders WHERE CusId = ?");
            foreach ($customers as $customer) {
                $orders_stmt->execute([$customer['CusId']]);
                $orders = $orders_stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($orders as $order): ?>
                    <option value="<?= htmlspecialchars($order['OCode']) ?>">
                        <?= htmlspecialchars("Order #" . $order['OCode'] . " - Total: $" . number_format($order['TotalPrice'], 2)) ?>
                    </option>
                <?php endforeach;
            }
        }
        ?>
    </select>

    <label for="amount">Số Tiền Thanh Toán (USD):</label>
    <input type="number" name="amount" step="0.01" required>

    <button type="submit">Xác Nhận Thanh Toán</button>
</form>
    </div>
</body>
</html>
