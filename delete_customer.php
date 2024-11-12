<?php
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

        // Delete related records in customer_payments
        $stmt = $conn->prepare("DELETE FROM customer_payments WHERE customer_id = ?");
        $stmt->execute([$id]);

        // Delete the customer record
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
?>
