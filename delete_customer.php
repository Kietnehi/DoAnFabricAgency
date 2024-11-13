<?php
ob_start();
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Include navigation

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Begin transaction
        $conn->beginTransaction();

        // Xóa các bản ghi liên quan trong `customer_payments` và `customer_care`
        $stmt = $conn->prepare("DELETE FROM customer_payments WHERE customer_id = ?");
        $stmt->execute([$id]);

        $stmt = $conn->prepare("DELETE FROM customer_care WHERE customer_id = ?");
        $stmt->execute([$id]);

        // Xóa bản ghi trong `customers`
        $stmt = $conn->prepare("DELETE FROM customers WHERE customer_id = ?");
        $stmt->execute([$id]);

        // Commit transaction
        $conn->commit();

        header("Location: customers.php"); // Redirect to customer list
        exit();
    } catch (PDOException $e) {
        // Rollback transaction if there’s an error
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
ob_end_flush();
?>
