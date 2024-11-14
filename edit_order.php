<?php
ob_start();
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Lấy `order_id` từ URL
$order_id = $_GET['id'];

// Lấy thông tin đơn hàng hiện tại từ cơ sở dữ liệu
$stmt = $conn->prepare("SELECT * FROM Orders WHERE order_id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Lấy danh sách khách hàng và nhân viên cho dropdown
$customers = $conn->query("SELECT customer_id, first_name, last_name FROM Customers")->fetchAll(PDO::FETCH_ASSOC);
$employees = $conn->query("SELECT employee_id, first_name, last_name FROM Employees")->fetchAll(PDO::FETCH_ASSOC);

// Lấy tổng số tiền đã thanh toán cho đơn hàng này
$paid_stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) FROM Order_Payments WHERE order_id = ?");
$paid_stmt->execute([$order_id]);
$total_paid = $paid_stmt->fetchColumn();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ biểu mẫu
    $customer_id = $_POST['customer_id'];
    $employee_id = $_POST['employee_id'];
    $order_date = $_POST['order_date'];
    $total_amount = $_POST['total_amount'];
    $status = $_POST['status'];

    // Kiểm tra nếu trạng thái là "paid" và tổng số tiền chưa được thanh toán đủ
    if ($status === 'paid' && $total_paid < $total_amount) {
        echo "<script>alert('Bạn phải thanh toán đủ số tiền mới có thể sửa trạng thái thành Paid.');</script>";
    } else {
        // Cập nhật thông tin đơn hàng
        $sql = "UPDATE Orders SET customer_id = ?, employee_id = ?, order_date = ?, total_amount = ?, status = ? WHERE order_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$customer_id, $employee_id, $order_date, $total_amount, $status, $order_id]);

        // Quay lại trang danh sách đơn hàng
        header("Location: orders.php");
        exit();
    }
}
ob_end_flush(); 
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Đơn Hàng</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Sửa Đơn Hàng</h1>
    <form action="edit_order.php?id=<?= $order_id ?>" method="POST">
        <label for="customer_id">Khách Hàng:</label>
        <select name="customer_id" required>
            <?php foreach ($customers as $customer): ?>
                <option value="<?= $customer['customer_id']; ?>" <?= $customer['customer_id'] == $order['customer_id'] ? 'selected' : '' ?>>
                    <?= $customer['first_name'] . " " . $customer['last_name']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="employee_id">Nhân Viên:</label>
        <select name="employee_id" required>
            <?php foreach ($employees as $employee): ?>
                <option value="<?= $employee['employee_id']; ?>" <?= $employee['employee_id'] == $order['employee_id'] ? 'selected' : '' ?>>
                    <?= $employee['first_name'] . " " . $employee['last_name']; ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="order_date">Ngày Đặt Hàng:</label>
        <input type="datetime-local" name="order_date" value="<?= date('Y-m-d\TH:i', strtotime($order['order_date'])); ?>" required>

        <label for="total_amount">Tổng Tiền:</label>
        <input type="number" name="total_amount" step="0.01" value="<?= $order['total_amount']; ?>" required>

        <label for="status">Trạng Thái:</label>
        <select name="status" required>
            <option value="new" <?= $order['status'] == 'new' ? 'selected' : '' ?>>New</option>
            <option value="ordered" <?= $order['status'] == 'ordered' ? 'selected' : '' ?>>Ordered</option>
            <option value="partial_payment" <?= $order['status'] == 'partial_payment' ? 'selected' : '' ?>>Partial Payment</option>
            <option value="paid" <?= $order['status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
            <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>

        <button type="submit">Lưu Thay Đổi</button>
    </form>
    <a href="orders.php">Quay lại Danh Sách Đơn Hàng</a>
</body>
</html>
