<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?><?php
require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng
// Lấy thông tin đơn hàng từ ID đơn hàng
$order_id = $_GET['id'];
$stmt = $conn->prepare("SELECT Orders.*, Customers.first_name AS cust_first, Customers.last_name AS cust_last,
                        Employees.first_name AS emp_first, Employees.last_name AS emp_last
                        FROM Orders
                        JOIN Customers ON Orders.customer_id = Customers.customer_id
                        JOIN Employees ON Orders.employee_id = Employees.employee_id
                        WHERE order_id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Lấy danh sách các cuộn vải trong đơn hàng
$stmt = $conn->prepare("SELECT Fabric_Rolls.roll_id, Fabric_Types.name AS fabric_name, Fabric_Rolls.length 
                        FROM Order_Fabric_Rolls 
                        JOIN Fabric_Rolls ON Order_Fabric_Rolls.roll_id = Fabric_Rolls.roll_id
                        JOIN Fabric_Types ON Fabric_Rolls.fabric_type_id = Fabric_Types.fabric_type_id
                        WHERE order_id = ?");
$stmt->execute([$order_id]);
$rolls = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi Tiết Đơn Hàng</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Chi Tiết Đơn Hàng</h1>
    <p><strong>Khách Hàng:</strong> <?= $order['cust_first'] . " " . $order['cust_last']; ?></p>
    <p><strong>Nhân Viên Bán Hàng:</strong> <?= $order['emp_first'] . " " . $order['emp_last']; ?></p>
    <p><strong>Ngày Đặt Hàng:</strong> <?= $order['order_date']; ?></p>
    <p><strong>Tổng Tiền:</strong> <?= $order['total_amount']; ?></p>
    <p><strong>Trạng Thái:</strong> <?= $order['status']; ?></p>

    <h2>Cuộn Vải trong Đơn Hàng</h2>
    <ul>
        <?php foreach ($rolls as $roll): ?>
            <li><?= $roll['fabric_name']; ?> - Dài: <?= $roll['length']; ?> mét</li>
        <?php endforeach; ?>
    </ul>

    <a href="orders.php">Quay Lại Danh Sách Đơn Hàng</a>
</body>
</html>
