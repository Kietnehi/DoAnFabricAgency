<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';

// Kiểm tra xem có ID đơn hàng hay không
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];

    // Truy vấn để kiểm tra sự tồn tại của đơn hàng
    $stmt = $conn->prepare("SELECT * FROM Orders WHERE order_id = :order_id");
    $stmt->execute(['order_id' => $order_id]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        // Xóa đơn hàng nếu đã xác nhận
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Xóa các dòng liên quan trong Order_Fabric_Rolls nếu có
            $conn->prepare("DELETE FROM Order_Fabric_Rolls WHERE order_id = :order_id")->execute(['order_id' => $order_id]);

            // Xóa các thanh toán liên quan trong Order_Payments nếu có
            $conn->prepare("DELETE FROM Order_Payments WHERE order_id = :order_id")->execute(['order_id' => $order_id]);

            // Xóa đơn hàng chính
            $stmt = $conn->prepare("DELETE FROM Orders WHERE order_id = :order_id");
            $stmt->execute(['order_id' => $order_id]);

            // Chuyển hướng sau khi xóa thành công
            header("Location: orders.php");
            exit();
        }
    } else {
        $error = "Đơn hàng không tồn tại!";
    }
} else {
    header("Location: orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xóa Đơn Hàng</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Xóa Đơn Hàng</h1>
    <?php if (isset($error)): ?>
        <p style="color: red;"><?= $error ?></p>
        <a href="orders.php">Quay lại danh sách đơn hàng</a>
    <?php else: ?>
        <p>Bạn có chắc chắn muốn xóa đơn hàng này không?</p>
        <form method="POST">
            <button type="submit" style="background-color: #f44336; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">Xóa Đơn Hàng</button>
            <a href="orders.php" style="margin-left: 15px; text-decoration: none; color: #333;">Hủy bỏ</a>
        </form>
    <?php endif; ?>
</body>
</html>
