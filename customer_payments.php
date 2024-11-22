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

// Xử lý form thêm thanh toán mới
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $amount = $_POST['amount'];
    $payment_date = date('Y-m-d H:i:s');

    // Thêm thanh toán vào bảng `customer_partialpayments`
    $sql = "INSERT INTO customer_partialpayments (CusId, Amount, PaymentTime) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$customer_id, $amount, $payment_date]);

    // Cập nhật công nợ của khách hàng
    $update_sql = "UPDATE customer SET Dept = Dept - ? WHERE CusId = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->execute([$amount, $customer_id]);

    // Kiểm tra công nợ hiện tại để cập nhật trạng thái cảnh báo nếu cần thiết
    $balance_check_sql = "SELECT Dept FROM customer WHERE CusId = ?";
    $balance_stmt = $conn->prepare($balance_check_sql);
    $balance_stmt->execute([$customer_id]);
    $outstanding_balance = $balance_stmt->fetchColumn();

    if ($outstanding_balance > 2000) {
        $warning_sql = "UPDATE customerstatus SET Alert = 1, AlertStartDate = COALESCE(AlertStartDate, CURDATE()) WHERE CusId = ?";
        $conn->prepare($warning_sql)->execute([$customer_id]);
    } else {
        $warning_sql = "UPDATE customerstatus SET Alert = 0, AlertStartDate = NULL WHERE CusId = ?";
        $conn->prepare($warning_sql)->execute([$customer_id]);
    }

    header("Location: customer_payments.php");
    exit();
}

// Lấy danh sách thanh toán với giới hạn phân trang
$stmt = $conn->prepare("
    SELECT cp.CusId, cp.Amount, cp.PaymentTime, c.Fname, c.Lname 
    FROM customer_partialpayments cp
    JOIN customer c ON cp.CusId = c.CusId 
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

// Lấy danh sách khách hàng cho form thêm thanh toán
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

        <!-- Payment list table with "View Details" button for each row -->
        <table>
            <tr>
                <th>Mã Khách Hàng</th>
                <th>Khách Hàng</th>
                <th>Ngày Thanh Toán</th>
                <th>Số Tiền (USD)</th>
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
                            <a href="customer_orders.php?customer_id=<?= htmlspecialchars($payment['CusId']); ?>" class="view-details-button">Xem Chi Tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Không có thanh toán nào.</td>
                </tr>
            <?php endif; ?>
        </table>

        <!-- Pagination links -->
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
                        <?= htmlspecialchars($customer['Fname'] . " " . $customer['Lname']) ?> - Công nợ: $<?= htmlspecialchars(number_format($customer['Dept'], 2)) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="amount">Số Tiền Thanh Toán (USD):</label>
            <input type="number" name="amount" step="0.01" required>

            <button type="submit">Xác Nhận Thanh Toán</button>
        </form>
    </div>
</body>
</html>
