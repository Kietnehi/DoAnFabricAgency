<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $amount = $_POST['amount'];
    $payment_date = date('Y-m-d');

    // Thêm thanh toán vào Customer_Payments
    $sql = "INSERT INTO Customer_Payments (customer_id, payment_date, amount) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$customer_id, $payment_date, $amount]);

    // Cập nhật công nợ của khách hàng
    $update_sql = "UPDATE Customers SET outstanding_balance = outstanding_balance - ? WHERE customer_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->execute([$amount, $customer_id]);

    // Kiểm tra công nợ hiện tại để cập nhật trạng thái cảnh báo
    $balance_check_sql = "SELECT outstanding_balance FROM Customers WHERE customer_id = ?";
    $balance_stmt = $conn->prepare($balance_check_sql);
    $balance_stmt->execute([$customer_id]);
    $outstanding_balance = $balance_stmt->fetchColumn();

    if ($outstanding_balance > 2000) {
        // Đặt cảnh báo nếu công nợ vượt quá $2000
        $warning_sql = "UPDATE Customers SET warning_status = 1, warning_start_date = COALESCE(warning_start_date, CURDATE()) WHERE customer_id = ?";
        $conn->prepare($warning_sql)->execute([$customer_id]);
    } else {
        // Tắt cảnh báo nếu công nợ không còn quá $2000
        $warning_sql = "UPDATE Customers SET warning_status = 0, warning_start_date = NULL WHERE customer_id = ?";
        $conn->prepare($warning_sql)->execute([$customer_id]);
    }

    header("Location: customer_payments.php");
    exit();
}

// Lấy danh sách khách hàng để hiển thị trong form
$customers = $conn->query("SELECT customer_id, first_name, last_name, outstanding_balance FROM Customers")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Thanh Toán Khách Hàng</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Thêm Thanh Toán Khách Hàng</h1>
        <form action="add_payment.php" method="POST">
            <label for="customer_id">Khách Hàng:</label>
            <select name="customer_id" required>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= htmlspecialchars($customer['customer_id']) ?>">
                        <?= htmlspecialchars($customer['first_name'] . " " . $customer['last_name']) ?> - Công nợ: $<?= htmlspecialchars(number_format($customer['outstanding_balance'], 2)) ?>
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
