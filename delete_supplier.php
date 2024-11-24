<?php
// Start session if it hasn't started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';

// Kiểm tra nếu ID nhà cung cấp đã được truyền vào
if (!isset($_GET['id'])) {
    header("Location: supplier.php");
    exit();
}

$supplierId = $_GET['id'];

try {
    // Xóa nhà cung cấp khỏi cơ sở dữ liệu
    $stmt = $conn->prepare("DELETE FROM supplier WHERE SCode = ?");
    $stmt->execute([$supplierId]);

    // Ghi thông báo thành công vào session
    $_SESSION['success_message'] = "Xóa nhà cung cấp thành công!";
    
    // Chuyển hướng về trang quản lý nhà cung cấp
    header("Location: supplier.php");
    exit();
} catch (PDOException $e) {
    echo "Lỗi: " . $e->getMessage();
}

$conn = null;
?>