<?php
ob_start();
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

if (isset($_GET['id'])) {
    $id = $_GET['id']; // ID của khách hàng cần xóa

    try {
        // Bắt đầu giao dịch
        $conn->beginTransaction();

        // Xóa các bản ghi liên quan trong `customer_partialpayments`
        $stmt = $conn->prepare("DELETE FROM customer_partialpayments WHERE CusId = ?");
        $stmt->execute([$id]);

        // Xóa các bản ghi liên quan trong `customerstatus`
        $stmt = $conn->prepare("DELETE FROM customerstatus WHERE CusId = ?");
        $stmt->execute([$id]);

        // Xóa các bản ghi liên quan trong `orders`
        $stmt = $conn->prepare("DELETE FROM orders WHERE CusId = ?");
        $stmt->execute([$id]);

        // Xóa bản ghi khách hàng trong bảng `customer`
        $stmt = $conn->prepare("DELETE FROM customer WHERE CusId = ?");
        $stmt->execute([$id]);

        // Hoàn tất giao dịch
        $conn->commit();

        // Chuyển hướng về trang danh sách khách hàng
        header("Location: customers.php");
        exit();
    } catch (PDOException $e) {
        // Hủy giao dịch nếu có lỗi
        $conn->rollBack();
        echo "Error: Không thể xóa khách hàng. " . $e->getMessage();
    }
} else {
    echo "Không tìm thấy ID khách hàng để xóa.";
}

ob_end_flush();
?>
