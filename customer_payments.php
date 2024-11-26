<?php
ob_start();
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'connect.php';
include 'nav.php'; // Include the navigation bar

// Thiết lập phân trang
$limit = 10; // Số bản ghi trên mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Nhận tham số order_by và order_dir từ URL
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'PaymentTime';
$order_dir = isset($_GET['order_dir']) && $_GET['order_dir'] === 'desc' ? 'desc' : 'asc';

// Xác định các cột hợp lệ để tránh SQL Injection
$valid_columns = ['CusId', 'Fname', 'Lname', 'PaymentTime', 'Amount'];
if (!in_array($order_by, $valid_columns)) {
    $order_by = 'PaymentTime';
}

// Xử lý form thêm thanh toán mới
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $order_code = $_POST['order_code'];
    $amount = $_POST['amount'];
    $payment_date = date('Y-m-d H:i:s');

    // Kiểm tra trạng thái đơn hàng
    $order_status_stmt = $conn->prepare("SELECT Status FROM orders WHERE OCode = ?");
    $order_status_stmt->execute([$order_code]);
    $order_status = $order_status_stmt->fetchColumn();

    if ($order_status === 'cancelled') {
        echo "<script>alert('Đơn hàng đã bị hủy. Không thể thanh toán cho đơn hàng này.'); window.location.href = 'customer_payments.php';</script>";
        exit();
    }

    // Lấy số tiền công nợ của khách hàng
    $debt_stmt = $conn->prepare("SELECT Dept FROM customer WHERE CusId = ?");
    $debt_stmt->execute([$customer_id]);
    $debt = $debt_stmt->fetchColumn();

    // Kiểm tra và điều chỉnh số tiền thanh toán nếu vượt quá số tiền công nợ
    if ($amount > $debt) {
        $amount = $debt;
    }

    // Thêm thanh toán vào bảng `customer_partialpayments`
    $sql = "INSERT INTO customer_partialpayments (CusId, Amount, PaymentTime, OCode) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$customer_id, $amount, $payment_date, $order_code]);

    // Cập nhật công nợ của khách hàng
    $update_sql = "UPDATE customer SET Dept = Dept - ? WHERE CusId = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->execute([$amount, $customer_id]);

    // Cập nhật trạng thái đơn hàng
    $order_update_stmt = $conn->prepare("
        UPDATE orders 
        SET Status = CASE 
            WHEN (SELECT COALESCE(SUM(Amount), 0) FROM customer_partialpayments WHERE OCode = ?) >= TotalPrice 
            THEN 'paid' 
            ELSE 'partial_payment' 
        END
        WHERE OCode = ?
    ");
    $order_update_stmt->execute([$order_code, $order_code]);

    header("Location: customer_payments.php");
    exit();
}

// Truy vấn SQL với sắp xếp
$stmt = $conn->prepare("
    SELECT cp.CusId, cp.Amount, cp.PaymentTime, cp.OCode, c.Fname, c.Lname 
    FROM customer_partialpayments cp
    JOIN customer c ON cp.CusId = c.CusId 
    ORDER BY $order_by $order_dir
    LIMIT :limit OFFSET :offset
");
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Lấy tổng số bản ghi để tính tổng số trang
$total_stmt = $conn->query("SELECT COUNT(*) FROM customer_partialpayments");
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $limit);

// Lấy danh sách khách hàng và đơn hàng
$customers = $conn->query("SELECT CusId, Fname, Lname, Dept FROM customer")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản lý Thanh Toán Khách Hàng</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="customer_payments.css">
    <style>
        .detail-btn {
            background: linear-gradient(to right, #007bff, #00d4ff); /* Gradient xanh dương */
            color: white;
            /* Màu chữ */
            border: none;
            /* Loại bỏ viền */
            padding: 8px 15px;
            /* Khoảng cách bên trong */
            font-size: 14px;
            /* Kích thước chữ */
            font-weight: bold;
            /* Chữ đậm */
            border-radius: 5px;
            /* Bo tròn góc */
            cursor: pointer;
            /* Con trỏ chuột */
            transition: all 0.3s ease;
            /* Hiệu ứng mượt mà */
            display: flex;
            /* Canh icon và text */
            align-items: center;
            /* Căn giữa theo trục dọc */
            gap: 5px;
            /* Khoảng cách giữa icon và text */
        }

        .detail-btn:hover {
            background: linear-gradient(to right, #0056b3, #009fbf); /* Gradient xanh đậm hơn */
            transform: scale(1.05);
            /* Phóng to nhẹ khi hover */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            /* Tạo bóng khi hover */
        }

        .detail-btn i {
            font-size: 16px;
            /* Kích thước icon */
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Danh Sách Thanh Toán Khách Hàng</h1>

        <table>
            <tr>
                <th>
                    <a href="?order_by=CusId&order_dir=<?= ($order_by === 'CusId' && $order_dir === 'asc') ? 'desc' : 'asc' ?>">
                        <i class="fas fa-sort"></i> Mã Khách Hàng
                    </a>
                </th>
                <th>
                    <a href="?order_by=Fname&order_dir=<?= ($order_by === 'Fname' && $order_dir === 'asc') ? 'desc' : 'asc' ?>">
                        <i class="fas fa-sort"></i> Khách Hàng
                    </a>
                </th>
                <th>
                    <a href="?order_by=PaymentTime&order_dir=<?= ($order_by === 'PaymentTime' && $order_dir === 'asc') ? 'desc' : 'asc' ?>">
                        <i class="fas fa-sort"></i> Ngày Thanh Toán
                    </a>
                </th>
                <th>
                    <a href="?order_by=Amount&order_dir=<?= ($order_by === 'Amount' && $order_dir === 'asc') ? 'desc' : 'asc' ?>">
                        <i class="fas fa-sort"></i> Số Tiền (USD)
                    </a>
                </th>
                <th>
                    <a href="?order_by=Status&order_dir=<?= ($order_by === 'Status' && $order_dir === 'asc') ? 'desc' : 'asc' ?>">
                        <i class="fas fa-sort"></i> Trạng Thái Đơn hàng
                </th>
                <th>Thao Tác</th>
            </tr>
            <?php if (!empty($payments)): ?>
                <?php foreach ($payments as $payment): ?>
                    <?php
                    // Lấy trạng thái đơn hàng
                    $order_stmt = $conn->prepare("SELECT Status FROM orders WHERE OCode = ?");
                    $order_stmt->execute([$payment['OCode']]);
                    $order_status = $order_stmt->fetchColumn();
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($payment['CusId']); ?></td>
                        <td><?= htmlspecialchars($payment['Fname'] . " " . $payment['Lname']); ?></td>
                        <td><?= htmlspecialchars($payment['PaymentTime']); ?></td>
                        <td>$<?= htmlspecialchars(number_format($payment['Amount'], 2)); ?> USD</td>
                        <td><?= $order_status === 'paid' ? 'Đã Hoàn Thành' : 'Chưa Hoàn Thành'; ?></td>
                        <td>
                            <button class="detail-btn" onclick="window.location.href='customer_orders.php?customer_id=<?= htmlspecialchars($payment['CusId']); ?>'">
                                <i class="fas fa-info-circle"></i> Xem Chi Tiết
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Không có thanh toán nào.</td>
                </tr>
            <?php endif; ?>
        </table>

        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>

        <h2>Thêm Thanh Toán Mới</h2>
<form action="customer_payments.php" method="POST" onsubmit="return validatePayment()">
    <label for="customer_id">Khách Hàng:</label>
    <select name="customer_id" required onchange="fetchOrders(this.value)">
        <option value="">-- Chọn khách hàng --</option>
        <?php foreach ($customers as $customer): ?>
            <option value="<?= htmlspecialchars($customer['CusId']) ?>">
                <?= htmlspecialchars($customer['Fname'] . " " . $customer['Lname']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="order_code">Mã Đơn Hàng:</label>
    <select name="order_code" id="order_code" required onchange="updateRemainingBalance()">
        <option value="">-- Chọn đơn hàng --</option>
    </select>

    <label for="amount">Số Tiền Thanh Toán (USD):</label>
    <input type="number" name="amount" step="0.01" required id="amount">

    <div id="remaining_balance" style="color: red; font-weight: bold;"></div>

    <button type="submit">Xác Nhận Thanh Toán</button>
</form>

<script>
    let selectedRemainingBalance = 0;

    function fetchOrders(customerId) {
        if (!customerId) {
            document.getElementById('order_code').innerHTML = '<option value="">-- Chọn đơn hàng --</option>';
            document.getElementById('remaining_balance').innerHTML = '';
            return;
        }

        // Fetch orders of the selected customer and display remaining balance
        fetch('fetch_orders.php?customer_id=' + customerId)
            .then(response => response.json())
            .then(data => {
                const orderCodeSelect = document.getElementById('order_code');
                orderCodeSelect.innerHTML = '<option value="">-- Chọn đơn hàng --</option>';

                if (data.length === 0) {
                    orderCodeSelect.innerHTML = '<option value="">Không có đơn hàng nào</option>';
                    document.getElementById('remaining_balance').innerHTML = '';
                } else {
                    data.forEach(order => {
                        // Add orders to the dropdown
                        orderCodeSelect.innerHTML += `<option value="${order.OCode}" data-remaining="${order.RemainingBalance}">
                            Order #${order.OCode} - ${order.TotalPrice} USD
                        </option>`;
                    });
                    // Reset remaining balance when new customer is selected
                    document.getElementById('remaining_balance').innerHTML = '';
                }
            })
            .catch(err => {
                console.error('Error fetching orders:', err);
            });
    }

    function updateRemainingBalance() {
        const orderSelect = document.getElementById('order_code');
        const selectedOption = orderSelect.options[orderSelect.selectedIndex];
        if (selectedOption) {
            // Get the remaining balance from the selected order
            selectedRemainingBalance = parseFloat(selectedOption.getAttribute('data-remaining'));
            document.getElementById('remaining_balance').innerHTML = 'Công nợ còn lại: ' + selectedRemainingBalance.toFixed(2) + ' USD';
        }
    }

    function validatePayment() {
        const amount = parseFloat(document.getElementById('amount').value);
        if (isNaN(amount) || amount <= 0) {
            alert('Vui lòng nhập số tiền thanh toán hợp lệ.');
            return false;
        }

        if (amount > selectedRemainingBalance) {
            alert('Số tiền thanh toán không được vượt quá công nợ của đơn hàng này.');
            return false;
        }

        return true;
    }
</script>