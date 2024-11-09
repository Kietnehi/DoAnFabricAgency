<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?><?php
require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM Customers WHERE customer_id = ?");
    $stmt->execute([$id]);

    header("Location: customers.php"); // Điều hướng về danh sách khách hàng
}
?>
