<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Bao gồm thanh điều hướng

// Lấy thông tin đơn hàng từ ID đơn hàng
$order_id = $_GET['id'];

// Truy vấn thông tin đơn hàng
$stmt = $conn->prepare("
    SELECT 
        orders.OCode, 
        orders.TotalPrice, 
        orders.OrderTime, 
        orders.Status, 
        customer.Fname AS cust_first, 
        customer.Lname AS cust_last, 
        employee.Fname AS emp_first, 
        employee.Lname AS emp_last
    FROM orders
    JOIN customer ON orders.CusId = customer.CusId
    JOIN employee ON orders.ECode = employee.ECode
    WHERE orders.OCode = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

// Truy vấn danh sách các chi tiết đơn hàng
$stmt = $conn->prepare("
    SELECT 
        order_detail.BCode, 
        order_detail.Quantity, 
        order_detail.UnitPrice, 
        order_detail.TotalPrice, 
        category.Name AS fabric_name, 
        bolt.Length AS fabric_length
    FROM order_detail
    JOIN bolt ON order_detail.BCode = bolt.BCode
    JOIN category ON bolt.CCode = category.CCode
    WHERE order_detail.OCode = ?
");
$stmt->execute([$order_id]);
$order_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi Tiết Đơn Hàng</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h1>Chi Tiết Đơn Hàng #<?= htmlspecialchars($order['OCode']) ?></h1>
            </div>
            <div class="card-body">
                <p><strong>Khách Hàng:</strong> <?= htmlspecialchars($order['cust_first'] . " " . $order['cust_last']) ?></p>
                <p><strong>Nhân Viên:</strong> <?= htmlspecialchars($order['emp_first'] . " " . $order['emp_last']) ?></p>
                <p><strong>Ngày Đặt Hàng:</strong> <?= htmlspecialchars($order['OrderTime']) ?></p>
                <p><strong>Tổng Tiền:</strong> <?= number_format($order['TotalPrice'], 2) ?> USD</p>
                <p><strong>Trạng Thái:</strong> <?= htmlspecialchars($order['Status']) ?></p>

                <h2 class="mt-4">Chi Tiết Sản Phẩm</h2>
                <table class="table table-striped mt-3">
                    <thead class="thead-dark">
                        <tr>
                            <th>Tên Loại Vải</th>
                            <th>Chiều Dài (m)</th>
                            <th>Số Lượng</th>
                            <th>Đơn Giá (USD)</th>
                            <th>Thành Tiền (USD)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_details as $detail): ?>
                            <tr>
                                <td><?= htmlspecialchars($detail['fabric_name']) ?></td>
                                <td><?= htmlspecialchars($detail['fabric_length']) ?></td>
                                <td><?= htmlspecialchars($detail['Quantity']) ?></td>
                                <td><?= number_format($detail['UnitPrice'], 2) ?></td>
                                <td><?= number_format($detail['TotalPrice'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <a href="orders.php" class="btn btn-primary mt-3"><i class="fa fa-arrow-left" aria-hidden="true"></i> Quay Lại Danh Sách Đơn Hàng</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>