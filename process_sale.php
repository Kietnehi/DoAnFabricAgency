<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?><?php
require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $employee_id = $_POST['employee_id'];
    $total_amount = $_POST['total_amount'];
    $fabric_rolls = $_POST['fabric_rolls'];
    $order_date = date('Y-m-d H:i:s');
    $status = 'new';

    // Tạo đơn hàng
    $sql = "INSERT INTO Orders (customer_id, employee_id, order_date, total_amount, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$customer_id, $employee_id, $order_date, $total_amount, $status]);

    // Lấy ID của đơn hàng vừa tạo
    $order_id = $conn->lastInsertId();

    // Thêm các cuộn vải vào đơn hàng
    foreach ($fabric_rolls as $roll_id) {
        $sql = "INSERT INTO Order_Fabric_Rolls (order_id, roll_id) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$order_id, $roll_id]);
    }

    // Chuyển đến trang chi tiết đơn hàng
    header("Location: order_details.php?id=$order_id");
    exit();
}
?>
