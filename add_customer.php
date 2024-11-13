<?php
ob_start();
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Thanh điều hướng

// Kiểm tra nếu form đã được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $outstanding_balance = $_POST['outstanding_balance'];
    $warning_status = isset($_POST['warning_status']) ? 1 : 0;
    $bad_debt_status = isset($_POST['bad_debt_status']) ? 1 : 0;
    $warning_start_date = $warning_status ? date('Y-m-d') : NULL;

    // Thêm thông tin khách hàng mới vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO customers (first_name, last_name, address, phone, outstanding_balance, warning_status, bad_debt_status, warning_start_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$first_name, $last_name, $address, $phone, $outstanding_balance, $warning_status, $bad_debt_status, $warning_start_date]);

    header("Location: customers.php"); // Chuyển hướng về trang danh sách khách hàng
    exit();
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Khách Hàng</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Thêm Khách Hàng Mới</h2>
        <form action="add_customer.php" method="POST">
            <div class="form-group">
                <label for="first_name">Tên:</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label for="last_name">Họ:</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label for="address">Địa Chỉ:</label>
                <input type="text" class="form-control" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="phone">Số Điện Thoại:</label>
                <input type="text" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="outstanding_balance">Công Nợ (USD):</label>
                <input type="number" class="form-control" id="outstanding_balance" name="outstanding_balance" step="0.01" required>
            </div>
            <div class="form-group">
                <label for="warning_status">Trạng Thái Cảnh Báo:</label>
                <input type="checkbox" id="warning_status" name="warning_status">
            </div>
            <div class="form-group">
                <label for="bad_debt_status">Nợ Xấu:</label>
                <input type="checkbox" id="bad_debt_status" name="bad_debt_status">
            </div>
            <button type="submit" class="btn btn-primary">Thêm Khách Hàng</button>
        </form>
    </div>
</body>
</html>
