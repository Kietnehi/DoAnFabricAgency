<?php
include "nav.php";
require 'connect.php';

if (!isset($_GET['customer_id'])) {
    echo "Customer ID is not specified.";
    exit();
}

$customer_id = $_GET['customer_id'];

// Get customer information
$customer_stmt = $conn->prepare("SELECT first_name, last_name FROM customers WHERE customer_id = ?");
$customer_stmt->execute([$customer_id]);
$customer = $customer_stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    echo "Customer not found.";
    exit();
}

// Get orders for the specified customer
$order_stmt = $conn->prepare("
    SELECT orders.order_id, orders.order_date, orders.total_amount, orders.status, orders.cancellation_reason
    FROM orders
    WHERE orders.customer_id = ?
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
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            width: 100%;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            background: #fff;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: #f9f9f9;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f4f4f4;
        }
        .no-data {
            text-align: center;
            font-style: italic;
            color: #888;
        }
        .toggle-btn {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 8px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .toggle-btn:hover {
            background-color: #0056b3;
        }
        .details-container {
            display: none;
            margin-top: 10px;
            background: #f0f0f0;
            padding: 10px;
            border-radius: 8px;
            animation: fadeIn 0.3s ease-in-out;
        }
        .nested-table {
            width: 100%;
            margin-top: 10px;
            border: none;
            background: none;
        }
        .nested-table th, .nested-table td {
            padding: 8px;
            text-align: left;
            border: none;
            background: #f7f7f7;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .status-paid { color: #28a745; font-weight: bold; }
        .status-new { color: #ffc107; font-weight: bold; }
        .status-partial_payment { color: #17a2b8; font-weight: bold; }
        .status-ordered { color: #6c757d; font-weight: bold; }
        .status-cancelled { color: #dc3545; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h1>Chi Tiết Đơn Hàng cho <?= htmlspecialchars($customer['first_name'] . " " . $customer['last_name']); ?></h1>

    <table>
        <tr>
            <th>ID Đơn Hàng</th>
            <th>Ngày Đặt</th>
            <th>Tổng Tiền (USD)</th>
            <th>Trạng Thái</th>
            <th>Chi Tiết</th>
        </tr>
        <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_id']); ?></td>
                    <td><?= htmlspecialchars($order['order_date']); ?></td>
                    <td>$<?= htmlspecialchars(number_format($order['total_amount'], 2)); ?></td>
                    <td class="status-<?= htmlspecialchars($order['status']); ?>">
                        <?= htmlspecialchars($order['status']); ?>
                        <?php if ($order['status'] === 'cancelled'): ?>
                            <br><em>Lý do: <?= htmlspecialchars($order['cancellation_reason']); ?></em>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="toggle-btn" onclick="toggleDetails(<?= $order['order_id']; ?>)">
                            Xem Chi Tiết
                        </button>
                    </td>
                </tr>
                <tr id="details-<?= $order['order_id']; ?>" class="details-container">
                    <td colspan="5">
                        <h3>Chi Tiết Vải</h3>
                        <table class="nested-table">
                            <tr >
                                <th style="color: black;">Tên Vải</th>
                                <th style="color: black;">Màu Sắc</th>
                                <th style="color: black;">Chiều Dài (m)</th>
                                <th style="color: black;">Giá/m</th>
                            </tr>
                            <?php
                            $roll_stmt = $conn->prepare("
                                SELECT fabric_types.name, fabric_types.color, fabric_rolls.length, fabric_types.current_price
                                FROM order_fabric_rolls
                                JOIN fabric_rolls ON order_fabric_rolls.roll_id = fabric_rolls.roll_id
                                JOIN fabric_types ON fabric_rolls.fabric_type_id = fabric_types.fabric_type_id
                                WHERE order_fabric_rolls.order_id = ?
                            ");
                            $roll_stmt->execute([$order['order_id']]);
                            $rolls = $roll_stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <?php if (!empty($rolls)): ?>
                                <?php foreach ($rolls as $roll): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($roll['name']); ?></td>
                                        <td><?= htmlspecialchars($roll['color']); ?></td>
                                        <td><?= htmlspecialchars($roll['length']); ?> m</td>
                                        <td>$<?= htmlspecialchars(number_format($roll['current_price'], 2)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="no-data">Không có vải</td></tr>
                            <?php endif; ?>
                        </table>
                        <br>
                        <h3>Chi Tiết Thanh Toán</h3>
                        <table class="nested-table">
                        <tr>
    <th style="color: black;">Ngày Thanh Toán</th>
    <th style="color: black;">Số Tiền (USD)</th>
</tr>

                            <?php
                            $payment_stmt = $conn->prepare("
                                SELECT payment_date, amount
                                FROM order_payments
                                WHERE order_id = ?
                            ");
                            $payment_stmt->execute([$order['order_id']]);
                            $payments = $payment_stmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            <?php if (!empty($payments)): ?>
                                <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($payment['payment_date']); ?></td>
                                        <td>$<?= htmlspecialchars(number_format($payment['amount'], 2)); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="no-data">Không có thanh toán</td></tr>
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
        detailsRow.classList.toggle('fade-in', !isVisible);
    }
</script>
</body>
</html>
