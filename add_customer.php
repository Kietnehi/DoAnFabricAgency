<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php
include 'nav.php'; // Bao gồm thanh điều hướng
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $phone = $_POST['phone'];
    $outstanding_balance = $_POST['outstanding_balance'];

    $sql = "INSERT INTO Customers (first_name, last_name, phone, outstanding_balance) VALUES (?, ?, ?, ?)";
    $stmt= $conn->prepare($sql);
    $stmt->execute([$first_name, $last_name, $phone, $outstanding_balance]);

    header("Location: customers.php"); // Điều hướng về danh sách khách hàng
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Khách Hàng Mới</title>
</head>
<body>
    <h1>Thêm Khách Hàng</h1>
    <form action="add_customer.php" method="POST">
        <label for="first_name">Tên:</label>
        <input type="text" name="first_name" required>
        <label for="last_name">Họ:</label>
        <input type="text" name="last_name" required>
        <label for="phone">Số Điện Thoại:</label>
        <input type="text" name="phone" required>
        <label for="outstanding_balance">Công Nợ:</label>
        <input type="number" name="outstanding_balance" step="0.01" required>
        <button type="submit">Thêm</button>
    </form>
</body>
</html>
