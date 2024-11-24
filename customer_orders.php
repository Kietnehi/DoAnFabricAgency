<?php
include "nav.php";
require 'connect.php';

if (!isset($_GET['customer_id'])) {
    echo "Customer ID is not specified.";
    exit();
}

$customer_id = $_GET['customer_id'];

// Get customer information
$customer_stmt = $conn->prepare("SELECT Fname, Lname FROM customer WHERE CusId = ?");
$customer_stmt->execute([$customer_id]);
$customer = $customer_stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    echo "Customer not found.";
    exit();
}

// Get orders for the specified customer
$order_stmt = $conn->prepare("
    SELECT OCode AS order_id, OrderTime AS order_date, TotalPrice AS total_amount, Status AS status, HandleTime
    FROM orders
    WHERE CusId = ?
");
$order_stmt->execute([$customer_id]);
$orders = $order_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi Tiết Đơn Hàng của Khách Hàng</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="customer_orders.css">
</head>
<body>
<div class="container">
    <h1>Chi Tiết Đơn Hàng cho <?= htmlspecialchars($customer['Fname'] . " " . $customer['Lname']); ?></h1>

    <table>
        <tr>
            <th>ID Đơn Hàng</th>
            <th>Ngày Đặt</th>
            <th>Tổng Tiền (USD)</th>
            <th>Trạng Thái</th>
            <th>Chi Tiết</th>
            <th>In PDF</th>
        </tr>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_id']); ?></td>
                    <td><?= htmlspecialchars($order['order_date']); ?></td>
                    <td>$<?= htmlspecialchars(number_format($order['total_amount'], 2)); ?></td>
                    <td class="status-<?= htmlspecialchars(strtolower($order['status'])); ?>">
                        <?= htmlspecialchars($order['status']); ?>
                    </td>
                    <td>
                        <button class="toggle-btn" onclick="toggleDetails(<?= $order['order_id']; ?>)">
                           <i class="fas fa-eye"></i> Xem
                        </button>
                    </td>
                    <td>
                        <a href="generate_order_pdf.php?order_id=<?= $order['order_id']; ?>" target="_blank">
                            Tải PDF
                        </a>
                    </td>
                </tr>
                <tr id="details-<?= $order['order_id']; ?>" class="details-container" style="display: none;">
                <td colspan="6">
    <h3>Thông Tin Đơn Hàng</h3>
    <p><strong>Nhân viên:</strong>
    <?php
    $employee_stmt = $conn->prepare("
        SELECT e.Fname, e.Lname
        FROM employee e
        JOIN orders o ON e.ECode = o.ECode
        WHERE o.OCode = ?
    ");
    $employee_stmt->execute([$order['order_id']]);
    $employee = $employee_stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <?= htmlspecialchars($employee['Fname'] . " " . $employee['Lname'] ?? 'Chưa có nhân viên xử lý'); ?>
    <p><strong>Thời Gian Xử Lý:</strong> <?= htmlspecialchars($order['HandleTime'] ?? 'Chưa xử lý'); ?></p>
    <p><strong>Trạng Thái:</strong> <?= htmlspecialchars($order['status']); ?></p>
    

    <h3>Chi Tiết Sản Phẩm</h3>
    <table class="nested-table">
        <tr>
            <th style="color: black;">Tên Vải</th>
            <th style="color: black;">Chiều Dài (m)</th>
            <th style="color: black;">Số Lượng</th>
            <th style="color: black;">Đơn Giá (USD)</th>
            <th style="color: black;">Tổng Giá (USD)</th>
        </tr>
        <?php
        $detail_stmt = $conn->prepare("
            SELECT c.Name AS fabric_name, b.Length AS length, od.Quantity AS quantity,
                   od.UnitPrice AS unit_price, od.TotalPrice AS total_price
            FROM order_detail od
            JOIN bolt b ON od.BCode = b.BCode
            JOIN category c ON b.CCode = c.CCode
            WHERE od.OCode = ?
        ");
        $detail_stmt->execute([$order['order_id']]);
        $details = $detail_stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <?php if (!empty($details)): ?>
            <?php foreach ($details as $detail): ?>
                <tr>
                    <td><?= htmlspecialchars($detail['fabric_name']); ?></td>
                    <td><?= htmlspecialchars($detail['length']); ?> m</td>
                    <td><?= htmlspecialchars($detail['quantity']); ?></td>
                    <td>$<?= htmlspecialchars(number_format($detail['unit_price'], 2)); ?></td>
                    <td>$<?= htmlspecialchars(number_format($detail['total_price'], 2)); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="no-data">Không có chi tiết sản phẩm.</td></tr>
        <?php endif; ?>
    </table>

    <h3>Lịch Sử Thanh Toán</h3>
    <table class="nested-table">
        <tr>
            <th style="color: black;">Ngày Thanh Toán</th>
            <th style="color: black;">Số Tiền (USD)</th>
        </tr>
        <?php
        $payment_stmt = $conn->prepare("
            SELECT PaymentTime, Amount
            FROM customer_partialpayments
            WHERE OCode = ?
        ");
        $payment_stmt->execute([$order['order_id']]);
        $payments = $payment_stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <?php if (!empty($payments)): ?>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td><?= htmlspecialchars($payment['PaymentTime']); ?></td>
                    <td>$<?= htmlspecialchars(number_format($payment['Amount'], 2)); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2" class="no-data">Không có lịch sử thanh toán.</td></tr>
        <?php endif; ?>
    </table>
</td>

                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="no-data">Không có đơn hàng nào cho khách hàng này.</td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<script>
    function toggleDetails(orderId) {
        const detailsRow = document.getElementById(`details-${orderId}`);
        const isVisible = detailsRow.style.display === "table-row";
        detailsRow.style.display = isVisible ? "none" : "table-row";
    }
</script>
</body>
</html>
