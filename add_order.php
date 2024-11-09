<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng
// Lấy danh sách khách hàng và nhân viên
$customers = $conn->query("SELECT customer_id, first_name, last_name FROM Customers")->fetchAll(PDO::FETCH_ASSOC);
$employees = $conn->query("SELECT employee_id, first_name, last_name FROM Employees")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $employee_id = $_POST['employee_id'];
    $order_date = date('Y-m-d H:i:s');
    $total_amount = $_POST['total_amount'];
    $status = 'new';

    $sql = "INSERT INTO Orders (customer_id, employee_id, order_date, total_amount, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$customer_id, $employee_id, $order_date, $total_amount, $status]);

    header("Location: orders.php");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Đơn Hàng Mới</title>
</head>
<body>
    <h1>Thêm Đơn Hàng</h1>
    <form action="add_order.php" method="POST">
        <label for="customer_id">Khách Hàng:</label>
        <select name="customer_id" required>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer['customer_id']; ?>">
                    <?= $customer['first_name'] . " " . $customer['last_name']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="employee_id">Nhân Viên:</label>
        <select name="employee_id" required>
            <?php foreach ($employees as $employee): ?>
                <option value="<?= $employee['employee_id']; ?>">
                    <?= $employee['first_name'] . " " . $employee['last_name']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="total_amount">Tổng Tiền:</label>
        <input type="number" name="total_amount" step="0.01" required>

        <button type="submit">Thêm Đơn Hàng</button>
    </form>
</body>
</html>
